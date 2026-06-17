<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Student documents compliance hub — upload, list and verify student records.
 * Documents are a sub-feature of the Students module, so they reuse students.*
 * abilities (there is no dedicated permission resource).
 */
class StudentDocumentController extends Controller implements HasMiddleware
{
    public const TYPES = ['CNIC / ID', 'Transcript', 'Passport', 'Certificate', 'Photo', 'Other'];

    public const STATUSES = ['pending', 'verified', 'rejected'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:students.view', only: ['index']),
            new Middleware('can:students.create', only: ['create', 'store']),
            new Middleware('can:students.edit', only: ['edit', 'update']),
            new Middleware('can:students.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = StudentDocument::query()->with('student.campus');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('document_code', 'like', "%{$term}%")
                ->orWhereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%")));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('document_type', $request->input('type'));
        }
        if ($request->filled('campus')) {
            $query->whereHas('student', fn ($s) => $s->where('campus_id', $request->input('campus')));
        }

        return view('admin.student-documents.index', [
            'documents' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => StudentDocument::count(),
                'verified' => StudentDocument::where('status', 'verified')->count(),
                'pending' => StudentDocument::where('status', 'pending')->count(),
                'rejected' => StudentDocument::where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.student-documents.create', [
            'students' => Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']),
            'selectedStudent' => $request->integer('student') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateDocument($request);

        $data['document_code'] = 'DOC-'.strtoupper(Str::random(8));
        $data['uploaded_by'] = $request->user()->name;
        $data['uploaded_at'] = now();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('student-documents', 'public');
            $data['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        $this->applyVerification($data, $request);

        StudentDocument::create($data);

        return redirect()->route('student-documents.index')->with('status', 'Document uploaded successfully.');
    }

    public function edit(StudentDocument $studentDocument): View
    {
        return view('admin.student-documents.edit', [
            'document' => $studentDocument->load('student'),
            'students' => Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']),
        ]);
    }

    public function update(Request $request, StudentDocument $studentDocument): RedirectResponse
    {
        $data = $this->validateDocument($request);

        if ($request->hasFile('file')) {
            if ($studentDocument->file_path) {
                Storage::disk('public')->delete($studentDocument->file_path);
            }
            $data['file_path'] = $request->file('file')->store('student-documents', 'public');
            $data['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        $this->applyVerification($data, $request);

        $studentDocument->update($data);

        return redirect()->route('student-documents.index')->with('status', 'Document updated successfully.');
    }

    public function destroy(StudentDocument $studentDocument): RedirectResponse
    {
        if ($studentDocument->file_path) {
            Storage::disk('public')->delete($studentDocument->file_path);
        }
        $studentDocument->delete();

        return back()->with('status', 'Document deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validateDocument(Request $request): array
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'document_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['nullable', 'in:pending,verified,rejected'],
            'verification_notes' => ['nullable', 'string', 'max:1000'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
        ]);

        // The binary file is persisted separately as file_path/file_type — never
        // as a column, so drop it from the attribute payload.
        unset($validated['file']);

        return $validated;
    }

    /**
     * Stamp the verifier/timestamp when a document is marked verified, and clear
     * it otherwise so the audit trail stays accurate.
     */
    private function applyVerification(array &$data, Request $request): void
    {
        $data['status'] = $data['status'] ?? 'pending';

        if ($data['status'] === 'verified') {
            $data['verified_by'] = $request->user()->id;
            $data['verified_at'] = now();
        } else {
            $data['verified_by'] = null;
            $data['verified_at'] = null;
        }
    }
}

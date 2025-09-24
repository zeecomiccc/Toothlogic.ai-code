@extends('backend.layouts.app')

@section('content')
    <x-backend.section-header>
        <x-slot name="toolbar">
            <div class="d-flex justify-content-end">
                <a href="{{ route('backend.dashboard') }}" class="btn btn-primary" data-type="ajax"
                    data-bs-toggle="tooltip">
                    {{ __('appointment.back') }}
                </a>
            </div>
        </x-slot>
    </x-backend.section-header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('Post-Operative Instructions Management') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Title</th>
                                        <th width="25%">Procedure Type</th>
                                        <th width="40%">Instructions</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($postInstructions as $instruction)
                                        <tr>
                                            <td>{{ $instruction->id }}</td>
                                            <td>
                                                <span class="fw-bold">{{ $instruction->title }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $instruction->procedure_type }}</span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;">
                                                    {!! strip_tags($instruction->post_instructions) !!}
                                                </div>
                                            </td>
                                            <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                            onclick="editInstruction({{ $instruction->id }}, '{{ $instruction->title }}', '{{ $instruction->procedure_type }}')"
                            data-instructions="{{ htmlspecialchars($instruction->post_instructions, ENT_QUOTES, 'UTF-8') }}">
                            <i class="ph ph-pencil"></i>
                        </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editInstructionModal" tabindex="-1" aria-labelledby="editInstructionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInstructionModalLabel">Edit Post-Operative Instructions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editInstructionForm" method="POST" action="{{ route('backend.update-post-instructions') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required>
                                </div>
                            </div>
                                                         <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="edit_procedure_type" class="form-label">Procedure Type</label>
                                     <input type="text" class="form-control" id="edit_procedure_type" name="procedure_type" readonly>
                                 </div>
                             </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="edit_post_instructions" class="form-label">Instructions</label>
                            <textarea class="form-control tinymce-edit" id="edit_post_instructions" name="post_instructions" rows="15" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="saveTinyMCEContent()">Update Instructions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('bottom_script')
    @push('after-scripts')
        <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Initialize TinyMCE for edit modal
                tinymce.init({
                    selector: '.tinymce-edit',
                    height: 400,
                    menubar: false,
                    plugins: 'autoresize link image code lists',
                    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image',
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    },
                    init_instance_callback: function(editor) {
                        // TinyMCE is ready
                        console.log('TinyMCE initialized for:', editor.id);
                    },
                    // Ensure content is displayed in visual mode, not code view
                    forced_root_block: 'p',
                    entity_encoding: 'raw',
                    // Remove code view button to prevent confusion
                    toolbar_mode: 'wrap'
                });
            });

                    function editInstruction(id, title, procedureType) {
            $('#edit_id').val(id);
            $('#edit_title').val(title);
            $('#edit_procedure_type').val(procedureType);

            // Get instructions from the clicked button's data attribute
            const button = event.target.closest('button');
            const instructions = button.getAttribute('data-instructions');
            
            // Show modal first
            $('#editInstructionModal').modal('show');
            
            // Set content after modal is shown
            setTimeout(function() {
                const editor = tinymce.get('edit_post_instructions');
                if (editor) {
                    // Decode HTML entities and set content
                    const decodedInstructions = decodeHTMLEntities(instructions);
                    editor.setContent(decodedInstructions);
                } else {
                    console.log('TinyMCE editor not found');
                }
            }, 300);
        }

        function saveTinyMCEContent() {
            // Save TinyMCE content to the textarea before form submission
            const editor = tinymce.get('edit_post_instructions');
            if (editor) {
                editor.save();
            }
        }

        function decodeHTMLEntities(text) {
            const textarea = document.createElement('textarea');
            textarea.innerHTML = text;
            return textarea.value;
        }
        </script>
    @endpush
@endsection

<div class="table-responsive rounded mb-0">
    <table class="table table-lg m-0" id="patient_history_table">
        <thead>
            <tr class="text-white">
                <th scope="col">{{ __('appointment.name') }}</th>
                <th scope="col">{{ __('appointment.date') }}</th>
                <th scope="col">{{ __('appointment.treatment_details') }}</th>
                <th scope="col">{{ __('appointment.radiograph_type') }}</th>
                <th scope="col">{{ __('appointment.radiograph_findings') }}</th>
                <th scope="col">{{ __('appointment.is_complete') }}</th>
                <th scope="col">{{ __('appointment.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($patientHistories->isEmpty())
                <tr>
                    <td colspan="7" class="text-center text-danger">No records found</td>
                </tr>
            @else
                @foreach ($patientHistories as $history)
                    <tr>
                        <td>{{ $history->demographic->full_name ?? ($history->user->full_name ?? '') }}</td>
                        <td>{{ $history->created_at ? $history->created_at->format('Y-m-d') : '' }}</td>
                        <td>{{ Illuminate\Support\Str::limit($history->medicalHistory->treatment_details ?? '', 20, '...') }}</td>
                        <td>
                            @if (!empty($history->radiographicExamination->radiograph_type))
                                @foreach ($history->radiographicExamination->radiograph_type as $type)
                                    <span class="badge bg-primary">{{ $type }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>


                        <td>{{ $history->radiographicExamination->radiograph_findings ?? '' }}</td>
                        <td>{{ $history->is_complete ? 'Yes' : 'No' }}</td>
                        <td class="action">
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn text-primary p-0 fs-5 me-2"
                                    onclick="editPatientHistory({{ $history->id }})" aria-controls="form-offcanvas">
                                    <i class="ph ph-pencil-simple-line"></i>
                                </button>
                                <button type="button" class="btn text-danger p-0 fs-5"
                                    onclick="deletePatientHistory({{ $history->id }}, 'Are you sure you want to delete this patient history?')"
                                    data-bs-toggle="tooltip">
                                    <i class="ph ph-trash"></i>
                                </button>
                                <button type="button" class="btn text-info p-0 fs-5"
                                    onclick="printPatientHistory({{ $history->id }})" title="Print">
                                    <i class="ph ph-printer"></i>
                                </button>
                                {{-- <button type="button" class="btn text-success p-0 fs-5"
                                    onclick="downloadPatientHistoryPDF({{ $history->id }})" title="Download PDF">
                                    <i class="ph ph-file-pdf"></i>
                                </button> --}}
                                <button type="button" class="btn text-success p-0 fs-5"
                                    onclick="downloadPatientHistoryHTML2PDF({{ $history->id }})" title="Download PDF">
                                    <i class="ph ph-file-pdf"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@push('after-scripts')
    <iframe id="print-iframe" style="display:none;"></iframe>
    
    <!-- html2pdf.js via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>
        var baseUrl = '{{ url('/') }}';

        function deletePatientHistory(id, message) {
            confirmDeleteSwal({
                message
            }).then((result) => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: baseUrl + '/app/encounter/delete-patient-history/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#patient_history_table').html(response.html);
                            Swal.fire({
                                title: 'Deleted',
                                text: response.message ||
                                    'Patient history deleted successfully.',
                                icon: 'success',
                                showClass: {
                                    popup: 'animate__animated animate__zoomIn'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__zoomOut'
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'Failed to delete the record.',
                                icon: 'error',
                                showClass: {
                                    popup: 'animate__animated animate__shakeX'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOut'
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'An unexpected error occurred.',
                            icon: 'error',
                            showClass: {
                                popup: 'animate__animated animate__shakeX'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOut'
                            }
                        });
                    }
                });
            });
        }

        function editPatientHistory(id) {
            $.ajax({
                url: baseUrl + '/app/encounter/edit-patient-history/' + id,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status && response.data) {
                        // Set the main patient_history_id
                        $('#patient_history_id').val(response.data.id);
                        // Set user_id and encounter_id if present
                        if (response.data.user_id) $('#patient_history_user_id').val(response.data.user_id);
                        if (response.data.encounter_id) $('#patient_history_encounter_id').val(response.data
                            .encounter_id);
                        // Prefill each step using fillStepData
                        if (typeof fillStepData === 'function') {
                            if (response.data.demographic) fillStepData('demographic', response.data
                                .demographic);
                            if (response.data.medical_history) fillStepData('medical-history', response.data
                                .medical_history);
                            if (response.data.dental_history) fillStepData('dental-history', response.data
                                .dental_history);
                            if (response.data.chief_complaint) fillStepData('chief-complaint', response.data
                                .chief_complaint);
                            if (response.data.clinical_examination) fillStepData('clinical-examination',
                                response.data.clinical_examination);
                            if (response.data.radiographic_examination) fillStepData('radiographic-examination',
                                response.data.radiographic_examination);
                            if (response.data.diagnosis_and_plan) fillStepData('diagnosis-plan', response.data
                                .diagnosis_and_plan);
                            if (response.data.jaw_treatment) fillStepData('dental-chart', response.data
                                .jaw_treatment);
                        }
                        // Open the modal
                        $('#addPatientHistory').modal('show');
                    } else {
                        alert(response.message || 'Failed to load patient history.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An unexpected error occurred.');
                }
            });
        }

        function printPatientHistory(id) {
            const url = baseUrl + '/app/encounter/print-patient-history/' + id;
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    let printFrame = document.getElementById('print-iframe');
                    if (!printFrame) {
                        printFrame = document.createElement('iframe');
                        printFrame.id = 'print-iframe';
                        printFrame.style.display = 'none';
                        document.body.appendChild(printFrame);
                    }
                    printFrame.onload = function() {
                        printFrame.contentWindow.focus();
                        printFrame.contentWindow.print();
                    };
                    printFrame.srcdoc = html;
                });
        }

        function downloadPatientHistoryPDF(id) {
            window.open(baseUrl + '/app/encounter/download-patient-history-pdf/' + id, '_blank');
        }

        function downloadPatientHistoryHTML2PDF(id) {
            // Show loading indicator
            Swal.fire({
                title: 'Generating PDF...',
                text: 'Please wait while we prepare your PDF',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Fetch the patient history HTML content
            const url = baseUrl + '/app/encounter/print-patient-history/' + id;
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Create a temporary container
                    const tempContainer = document.createElement('div');
                    tempContainer.innerHTML = html;
                    
                    // Find the main content (excluding scripts and unnecessary elements)
                    const mainContent = tempContainer.querySelector('body') || tempContainer;
                    
                    // Create a clean container for PDF generation
                    const pdfContainer = document.createElement('div');
                    pdfContainer.id = 'pdf-content';
                    pdfContainer.innerHTML = mainContent.innerHTML;

                    // Force color rendering for print/canvas
                    const colorStyle = document.createElement('style');
                    colorStyle.textContent = `* { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }`;
                    pdfContainer.prepend(colorStyle);

                    // Ensure CORS for images/SVGs
                    Array.from(pdfContainer.querySelectorAll('img')).forEach(function(img){
                        try { img.setAttribute('crossorigin', 'anonymous'); } catch(e) {}
                    });

                    // Attempt to extract embedded JSON for jaw data from inline script and apply colors
                    try {
                        const scripts = tempContainer.querySelectorAll('script');
                        let scriptText = '';
                        scripts.forEach(s => { scriptText += (s.textContent || ''); });

                        const upperMatch = scriptText.match(/const\s+upperJawData\s*=\s*(\{[\s\S]*?\}|\[[\s\S]*?\]);/);
                        const lowerMatch = scriptText.match(/const\s+lowerJawData\s*=\s*(\{[\s\S]*?\}|\[[\s\S]*?\]);/);
                        const upperJawData = upperMatch ? JSON.parse(upperMatch[1]) : {};
                        const lowerJawData = lowerMatch ? JSON.parse(lowerMatch[1]) : {};

                        const applyToothColor = (svg, treatmentType) => {
                            switch (treatmentType) {
                                case 'cavity':
                                    svg.style.backgroundColor = 'rgba(255, 68, 68, 0.3)';
                                    svg.style.borderColor = '#ff4444';
                                    break;
                                case 'crown':
                                    svg.style.backgroundColor = 'rgba(66, 133, 244, 0.3)';
                                    svg.style.borderColor = '#4285f4';
                                    break;
                                case 'missing':
                                    svg.style.backgroundColor = 'rgba(255, 187, 51, 0.3)';
                                    svg.style.borderColor = '#ffbb33';
                                    break;
                                case 'retained':
                                    svg.style.backgroundColor = 'rgba(0, 200, 81, 0.3)';
                                    svg.style.borderColor = '#00c851';
                                    break;
                            }
                        };

                        const applySet = (dataObj) => {
                            Object.keys(dataObj || {}).forEach(category => {
                                if (category.startsWith('mark_')) {
                                    const treatmentType = category.replace('mark_', '');
                                    const teeth = dataObj[category];
                                    if (Array.isArray(teeth)) {
                                        teeth.forEach(toothId => {
                                            const toothElement = pdfContainer.querySelector(`[data-tooth="${String(toothId).toLowerCase()}"]`);
                                            if (toothElement) {
                                                const svg = toothElement.querySelector('.tooth-svg');
                                                if (svg) applyToothColor(svg, treatmentType);
                                            }
                                        });
                                    }
                                }
                            });
                        };

                        applySet(upperJawData);
                        applySet(lowerJawData);
                    } catch (e) {
                        console.warn('Could not parse/apply jaw data from script:', e);
                    }

                    // Add some basic styling for PDF container
                    pdfContainer.style.padding = '20px';
                    pdfContainer.style.fontFamily = 'Arial, sans-serif';
                    pdfContainer.style.fontSize = '12px';
                    pdfContainer.style.lineHeight = '1.4';
                    pdfContainer.style.backgroundColor = '#ffffff';
                    
                    // Temporarily add to DOM
                    document.body.appendChild(pdfContainer);
                    
                    // Configure PDF options
                    const opt = {
                        margin: 0.5,
                        filename: 'patient_history_' + id + '.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { 
                            scale: 2, 
                            useCORS: true,
                            backgroundColor: '#ffffff',
                            logging: false
                        },
                        jsPDF: { 
                            unit: 'in', 
                            format: 'a4', 
                            orientation: 'portrait' 
                        }
                    };
                    
                    // Generate and download PDF
                    html2pdf().set(opt).from(pdfContainer).save().then(() => {
                        document.body.removeChild(pdfContainer);
                        Swal.fire({ title: 'Success!', text: 'PDF downloaded successfully', icon: 'success', timer: 1500, showConfirmButton: false });
                    }).catch(error => {
                        // Fallback using direct html2canvas + jsPDF
                        try {
                            const jspdf = window.jspdf || window.jsPDF ? window.jspdf : null;
                            const JSConstructor = jspdf && jspdf.jsPDF ? jspdf.jsPDF : window.jsPDF;
                            html2canvas(pdfContainer, { scale: 2, useCORS: true, backgroundColor: '#ffffff', logging: false }).then(canvas => {
                                const imgData = canvas.toDataURL('image/png');
                                const doc = new JSConstructor('p', 'mm', 'a4');
                                const imgProps = doc.getImageProperties(imgData);
                                const pdfWidth = doc.internal.pageSize.getWidth();
                                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                                doc.save('patient_history_' + id + '.pdf');
                                document.body.removeChild(pdfContainer);
                                Swal.fire({ title: 'Success!', text: 'PDF downloaded successfully', icon: 'success', timer: 1500, showConfirmButton: false });
                            }).catch(err2 => {
                                document.body.removeChild(pdfContainer);
                                Swal.fire({ title: 'Error', text: 'Failed to generate PDF. Please try again.', icon: 'error' });
                                console.error('PDF generation error (fallback):', err2);
                            });
                        } catch (err) {
                            document.body.removeChild(pdfContainer);
                            Swal.fire({ title: 'Error', text: 'Failed to generate PDF. Please try again.', icon: 'error' });
                            console.error('PDF generation error:', error, err);
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to load patient history data.',
                        icon: 'error'
                    });
                    console.error('Fetch error:', error);
                });
        }
    </script>
@endpush

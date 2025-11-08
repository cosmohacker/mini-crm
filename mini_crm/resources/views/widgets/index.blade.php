<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('tailwind/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/icons/tabler-icons/tabler-icons.css') }}">

    <title>Feedback Widget - {{ config('app.name', 'Mini CRM') }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .widget-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: transparent;
        }

        @media (max-width: 640px) {
            .widget-container {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="widget-container">
        <div class="card max-w-md w-full">
            <div class="card-body">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-500 mb-2">Send Feedback</h2>
                </div>
                <form id="feedbackForm">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm mb-2 text-gray-400">Name</label>
                        <input type="text" id="name" name="name" required
                            class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0"
                            placeholder="Your name">
                        <span class="text-red-500 text-xs hidden" id="name-error"></span>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm mb-2 text-gray-400">Email</label>
                        <input type="email" id="email" name="email" required
                            class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0"
                            placeholder="your.email@example.com">
                        <span class="text-red-500 text-xs hidden" id="email-error"></span>
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm mb-2 text-gray-400">Phone</label>
                        <input type="tel" id="phone" name="phone"
                            class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0"
                            placeholder="Your phone number">
                        <span class="text-red-500 text-xs hidden" id="phone-error"></span>
                    </div>

                    <!-- Subject -->
                    <div class="mb-4">
                        <label for="subject" class="block text-sm mb-2 text-gray-400">Subject</label>
                        <input type="text" id="subject" name="subject" required
                            class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0"
                            placeholder="What's this about?">
                        <span class="text-red-500 text-xs hidden" id="subject-error"></span>
                    </div>

                    <!-- Message -->
                    <div class="mb-4">
                        <label for="text" class="block text-sm mb-2 text-gray-400">Message</label>
                        <textarea id="text" name="text" rows="4" required
                            class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0"
                            placeholder="Tell us more..."></textarea>
                        <span class="text-red-500 text-xs hidden" id="text-error"></span>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-6">
                        <label for="image" class="block text-sm mb-2 text-gray-400">Image (Optional)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                        <span class="text-red-500 text-xs hidden" id="image-error"></span>
                        <p class="text-xs text-gray-400 mt-1">Accepted formats: JPG, PNG, GIF (Max 5MB)</p>
                        <div id="image-preview" class="mt-2 hidden">
                            <img id="preview-img" src="" alt="Preview"
                                class="max-w-full h-32 object-cover rounded-sm border border-gray-200">
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div id="success-message"
                        class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-sm">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-check text-lg"></i>
                            <span>Thank you! Your feedback has been submitted successfully.</span>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message"
                        class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-sm">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-alert-circle text-lg"></i>
                            <span id="error-text">Something went wrong. Please try again.</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-btn"
                        class="btn py-[10px] text-base text-white font-medium hover:bg-blue-700 w-full">
                        <span id="submit-text">Submit Feedback</span>
                        <span id="submit-loading" class="hidden">
                            <i class="ti ti-loader animate-spin mr-2"></i> Sending...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            // Get CSRF token
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Image preview
            $('#image').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        $('#image-error').text('Image size must be less than 5MB').removeClass('hidden');
                        $(this).val('');
                        $('#image-preview').addClass('hidden');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-img').attr('src', e.target.result);
                        $('#image-preview').removeClass('hidden');
                    };
                    reader.readAsDataURL(file);
                    $('#image-error').addClass('hidden');
                } else {
                    $('#image-preview').addClass('hidden');
                }
            });

            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();

                // Reset messages
                $('#success-message').addClass('hidden');
                $('#error-message').addClass('hidden');
                $('.text-red-500').addClass('hidden').text('');
                $('.border-red-500').removeClass('border-red-500');

                // Show loading state
                $('#submit-btn').prop('disabled', true);
                $('#submit-text').addClass('hidden');
                $('#submit-loading').removeClass('hidden');

                // Get form data
                const formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    subject: $('#subject').val(),
                    text: $('#text').val()
                };

                // Find or create customer first
                $.ajax({
                    url: '/api/customers',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify({
                        name: formData.name,
                        email: formData.email,
                        phone: formData.phone
                    }),
                    success: function(customerResponse) {
                        // Create FormData for ticket with image
                        const ticketFormData = new FormData();
                        ticketFormData.append('customer_id', customerResponse.id ||
                            customerResponse.data?.id);
                        ticketFormData.append('subject', formData.subject);
                        ticketFormData.append('text', formData.text);
                        ticketFormData.append('status', 'new');

                        const imageFile = $('#image')[0].files[0];
                        if (imageFile) {
                            ticketFormData.append('image', imageFile);
                        }

                        // Create ticket with customer_id and image
                        $.ajax({
                            url: '/api/tickets',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            processData: false,
                            contentType: false,
                            data: ticketFormData,
                            success: function(response) {
                                // Show success message
                                $('#success-message').removeClass('hidden');
                                $('#feedbackForm')[0].reset();
                                $('#image-preview').addClass('hidden');

                                // Reset button
                                $('#submit-btn').prop('disabled', false);
                                $('#submit-text').removeClass('hidden');
                                $('#submit-loading').addClass('hidden');
                            },
                            error: function(xhr) {
                                handleError(xhr,
                                    'Failed to submit ticket. Please try again.'
                                    );
                            }
                        });
                    },
                    error: function(xhr) {
                        // If customer exists, try to get existing customer and create ticket
                        if (xhr.status === 422 || xhr.status === 409) {
                            // Try to find customer by email
                            $.ajax({
                                url: '/api/customers?email=' + encodeURIComponent(
                                    formData.email),
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json'
                                },
                                success: function(customers) {
                                    const customer = Array.isArray(customers) ?
                                        customers[0] : customers.data?.[0];
                                    if (customer) {
                                        // Create FormData for ticket with image
                                        const ticketFormData = new FormData();
                                        ticketFormData.append('customer_id',
                                            customer.id);
                                        ticketFormData.append('subject', formData
                                            .subject);
                                        ticketFormData.append('text', formData
                                        .text);
                                        ticketFormData.append('status', 'new');

                                        const imageFile = $('#image')[0].files[0];
                                        if (imageFile) {
                                            ticketFormData.append('image',
                                                imageFile);
                                        }

                                        // Create ticket with existing customer and image
                                        $.ajax({
                                            url: '/api/tickets',
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Accept': 'application/json'
                                            },
                                            processData: false,
                                            contentType: false,
                                            data: ticketFormData,
                                            success: function(response) {
                                                $('#success-message')
                                                    .removeClass(
                                                        'hidden');
                                                $('#feedbackForm')[0]
                                                    .reset();
                                                $('#image-preview')
                                                    .addClass('hidden');
                                                $('#submit-btn').prop(
                                                    'disabled',
                                                    false);
                                                $('#submit-text')
                                                    .removeClass(
                                                        'hidden');
                                                $('#submit-loading')
                                                    .addClass('hidden');
                                            },
                                            error: function(xhr) {
                                                handleError(xhr,
                                                    'Failed to submit ticket. Please try again.'
                                                    );
                                            }
                                        });
                                    } else {
                                        handleError(xhr,
                                            'Unable to process your request. Please try again.'
                                            );
                                    }
                                },
                                error: function() {
                                    handleError(xhr,
                                        'Unable to process your request. Please try again.'
                                        );
                                }
                            });
                        } else {
                            handleError(xhr, 'Failed to create customer. Please try again.');
                        }
                    }
                });
            });

            function handleError(xhr, defaultMessage) {
                let errorMessage = defaultMessage;

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        // Handle rate limiting errors (customer_id field)
                        if (errors.customer_id && errors.customer_id.length > 0) {
                            errorMessage = errors.customer_id[0];
                        } else {
                            // Handle other field errors
                            Object.keys(errors).forEach(function(field) {
                                const errorElement = $('#' + field + '-error');
                                if (errorElement.length) {
                                    errorElement.text(errors[field][0]).removeClass('hidden');
                                    $('#' + field).addClass('border-red-500');
                                } else if (field !== 'customer_id') {
                                    // If field error element doesn't exist, add to main error message
                                    if (errorMessage === defaultMessage) {
                                        errorMessage = errors[field][0];
                                    }
                                }
                            });
                            if (errorMessage === defaultMessage && Object.keys(errors).length > 0) {
                                errorMessage = 'Please correct the errors above.';
                            }
                        }
                    }
                }

                $('#error-text').text(errorMessage);
                $('#error-message').removeClass('hidden');

                // Reset button
                $('#submit-btn').prop('disabled', false);
                $('#submit-text').removeClass('hidden');
                $('#submit-loading').addClass('hidden');
            }
        });
    </script>
</body>

</html>

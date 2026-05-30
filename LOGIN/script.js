function showForm(formId) {
    // Hide all forms
    document.querySelectorAll('.form-box').forEach(form => {form.classList.remove('active');});
    // Show selected form
    document.getElementById(formId).classList.add('active');
}
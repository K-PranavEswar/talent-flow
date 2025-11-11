<form method="post" action="<?= APP_URL ?>/onboarding/submit" class="card p-4 shadow-sm">
  <h4 class="mb-3 fw-bold text-primary">New Employee Onboarding</h4>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input name="name" class="form-control" placeholder="Enter full name" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Corporate Email</label>
      <input name="email" type="email" class="form-control" placeholder="example@company.com" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Role</label>
      <input name="role" class="form-control" value="Data Analyst" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Start Date</label>
      <input type="date" name="start_date" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Location</label>
      <input name="location" class="form-control" value="Bengaluru" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Manager Email</label>
      <input name="manager_email" class="form-control" value="talentflowxyz@gmail.com" readonly>
    </div>

    <div class="col-md-6">
      <label class="form-label">Hardware Bundle</label>
      <select name="bundle" class="form-select">
        <option>DataAnalyst</option>
        <option>Engineer</option>
        <option>HRGeneralist</option>
      </select>
    </div>
  </div>

  <div class="mt-4 text-end">
    <button class="btn btn-primary px-4">Run Onboarding</button>
    <a href="<?= APP_URL ?>" class="btn btn-outline-secondary px-4">Cancel</a>
  </div>
</form>

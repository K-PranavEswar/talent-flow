<form method="post" action="<?= APP_URL ?>/offer/submit" class="card p-3 shadow-sm">
  <h4 class="mb-3">Generate Offer Letter</h4>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Candidate Name</label>
      <input name="candidate" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Candidate Email</label>
      <input type="email" name="email" class="form-control" placeholder="candidate@domain.com" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Role</label>
      <input name="role" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">CTC (₹)</label>
      <input name="ctc" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Start Date</label>
      <input type="date" name="start_date" class="form-control" required>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-warning">Generate Offer</button>
    <a href="<?= APP_URL ?>" class="btn btn-outline-secondary">Cancel</a>
  </div>
</form>

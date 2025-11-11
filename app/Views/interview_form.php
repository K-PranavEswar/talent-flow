<form method="post" action="<?= APP_URL ?>/interview/submit" class="card p-3 shadow-sm">
  <h4 class="mb-3">Schedule Interview</h4>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Candidate Name</label>
      <input name="candidate" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Role</label>
      <input name="role" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Interview Panel Emails</label>
      <input name="panel" class="form-control" placeholder="e.g. user1@company.com,user2@company.com" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Preferred Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-success">Schedule Interview</button>
    <a href="<?= APP_URL ?>" class="btn btn-outline-secondary">Cancel</a>
  </div>
</form>

<div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
  <div class="page-content vertical-align-middle">
    <div class="row" style="margin-top: 120px;">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <?php if($success): ?>
          <div style="background:#f0fff0; border:1px solid #4CAF50; border-radius:8px; padding:40px;">
            <h2 style="color:#4CAF50;">&#10003; Account Activated!</h2>
            <p style="font-size:16px;"><?php echo $message; ?></p>
            <a href="<?php echo base_url(); ?>" class="btn btn-success" style="margin-top:20px;">Go to Login</a>
          </div>
        <?php else: ?>
          <div style="background:#fff0f0; border:1px solid #e74c3c; border-radius:8px; padding:40px;">
            <h2 style="color:#e74c3c;">&#10007; Activation Failed</h2>
            <p style="font-size:16px;"><?php echo $message; ?></p>
            <a href="<?php echo base_url(); ?>" class="btn btn-primary" style="margin-top:20px;">Go to Login</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

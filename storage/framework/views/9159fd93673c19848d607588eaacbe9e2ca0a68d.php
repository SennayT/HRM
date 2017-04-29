<?php $__env->startSection('title', 'Project Create'); ?>
<?php $__env->startSection('extraStyle'); ?>
  <link type="text/css" rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/libs/select2/select2.css" />
  <link type="text/css" rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/libs/bootstrap-datepicker/datepicker3.css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <section>
    <div class="section-header">
      <ol class="breadcrumb">
        <li><a href="<?php echo e(URL::Route('flat.index')); ?>">Flats</a></li>
        <li class="active">Create</li>
      </ol>
    </div><!--end .section-header -->
    <div class="section-body">
      <section>
        <div class="section-body">
          <!-- BEGIN HORIZONTAL FORM -->
          <div class="row">
            <div class="col-lg-12">
              <form class="form form-validate floating-label"
                    novalidate="novalidate"
                    action="<?php echo e(URL::route('flat.store')); ?>"
                    method="POST"
                    enctype="multipart/form-data">

                <div class="card">
                  <div class="card-head style-primary">
                    <header>Flat allocation</header>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <?php echo e(csrf_field()); ?>

                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <?php echo Form::select('projectType', ['' => '', 'Commerical' => 'Commerical','Residential' =>'Residential'], null, ['class' => 'form-control select2-list', 'required' => 'required']); ?>

                          <label for="projectType">Project Type</label>
                          <p class="help-block"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <select id="projects_id" class="form-control select2-list" name="projects_id" required>
                            <option value=""></option>
                          </select>
                          <label for="projectType">Project</label>
                          <p class="help-block"></p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <input type="text" class="form-control datepicker" value="<?php echo e($today->format('d/m/Y')); ?>" name="entryDate" required>
                          <label for="dateOfEntry">Date of entry</label>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <?php echo Form::select('floor', $floors, null, ['class' => 'form-control select2-list', 'required' => 'required']); ?>

                          <label for="floor">Floor</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <?php echo Form::select('type', $types, null, ['class' => 'form-control select2-list', 'required' => 'required']); ?>

                          <label for="floor">Flat Type</label>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <input type="text" class="form-control"  name="size" data-rule-number="true" required>
                          <label for="size">Flat size(Sft.)</label>
                          <p class="help-block">Numbers only</p>
                        </div>
                      </div>
                    </div>
                     <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <span class="radio-inline radio-styled radio-info">
                            <input type="radio" name="parking" checked value="No"><span>No</span>
                          </span>
                          <span class="radio-inline radio-styled radio-info">
                            <input type="radio" name="parking" value="Yes"><span>Yes</span>
                          </span>
                          <label for="parking">Parking</label>

                        </div>
                        </div>
                        <div class="col-lg-6" style="display: none">
                          <div class="form-group">
                            <input type="text" class="form-control"  name="parkingNo" data-rule-number="true" required>
                            <label for="parkingNo">Parking no</label>
                            <p class="help-block">Numbers only</p>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <textarea class="form-control"  name="description" rows="1"  maxlength="1000"></textarea>
                          <label for="description">Description</label>
                          <p class="help-block">max: 1000 letters</p>
                        </div>
                      </div>
                      </div>


                    <div class="form-group">
                      <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                          <strong>Whoops!</strong> There were some problems with your input.<br><br>
                          <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                              <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                          </ul>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div><!--end .card-body -->
                  <div class="card-actionbar">
                    <div class="card-actionbar-row">
                      <button type="submit" class="btn btn-primary ink-reaction"><i class="md md-add-circle-outline"></i> Create</button>
                    </div>
                  </div>
                </div><!--end .card -->
              </form>
            </div><!--end .col -->
          </div><!--end .row -->
          <!-- END HORIZONTAL FORM -->
        </div>
      </section>
    </div>

  </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extraScript'); ?>
  <script src="<?php echo e(url('/')); ?>/assets/js/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/js/libs/select2/select2.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/js/libs/jquery-validation/jquery.validate.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/js/libs/jquery-validation/additional-methods.min.js"></script>

  <script type="text/javascript">
      $( document ).ready(function() {
          $('select').select2();
          $('.datepicker').datepicker({
              format: 'dd/mm/yyyy',
              autoclose: true,
              todayHighlight : true

          });
          $('.radio-styled').click(function () {
             $(this).children('input').attr('checked', true);
          });
      });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
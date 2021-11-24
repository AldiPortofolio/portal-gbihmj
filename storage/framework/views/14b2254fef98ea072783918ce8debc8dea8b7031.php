
<?php $__env->startSection('content'); ?>
<div class="portlet light bordered">
  <div class="portlet-title">
      <div class="caption font-green portlet-container">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> <?php echo e($title); ?></span>
        <div class="head-button">
          <a href="<?php echo e(url($path)); ?>"><button class="btn red-mint"><?php echo e(trans('general.back')); ?></button></a>
        </div>
      </div>
  </div>
  <div class="portlet-body form">
    <form role="form" method="post" action="<?php echo e(url($path)); ?>/<?php echo e($useraccess->id); ?>" enctype="multipart/form-data">
      <?php echo e(method_field('PUT')); ?>

      <?php echo $__env->make('admin.includes.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <div class="form-group">
         <?php echo view($view_path.'.builder.text',['name' => 'access_name','label' => 'Access Name','attribute' => 'required','value' => $useraccess->access_name]); ?>

      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary checkall" data-target="access">Checkall</button>
        <button type="button" class="btn btn-primary uncheckall" data-target="access">Uncheckall</button>
      </div>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td>Menu</td>
                <td align="center">Active</td>
                <td align="center">View</td>
                <td align="center">Create</td>
                <td align="center">Edit</td>
                <td align="center">Delete</td>
                <td align="center">Sorting</td>
                <td align="center">Export</td>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $prmenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <tr>
                  <td><b><?php echo e($pr->menu_name); ?></b></td>
                  <td align="center">
                    <div class="form-md-checkboxes">
                      <div class="md-checkbox-inline">
                        <div class="md-checkbox">
                          <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>" class="md-check access" name="fkuseraccessid[]" <?php echo e(array_key_exists($pr->id,$dataau) ? 'checked' : ''); ?>  <?php echo e($pr->id == '1' ? 'required checked' : ''); ?> value="<?php echo e($pr->id); ?>">
                          <label for="checkbox_form_<?php echo e($pr->id); ?>">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td colspan="6"></td>
                </tr>
                <?php if(count($chmenu[$pr->id]) > 0): ?>
                  <?php $__currentLoopData = $chmenu[$pr->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <tr>
                      <td>
                        <div style="padding-left:10%;">
                          <?php echo e($ch->menu_name); ?>

                        </div>
                      </td>
                      <td align="center">
                        <div class="form-group form-md-checkboxes">
                          <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                              <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch); ?>" class="md-check access" name="fkuseraccessid[]" <?php echo e(isset($dataau[$ch->id]) ? ($ch->id == $dataau[$ch->id]->menu_id ? 'checked' : '') : ''); ?> value="<?php echo e($ch->id); ?>">
                              <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch); ?>">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td align="center">
                         <?php if($ch->view == 'y'): ?>
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_v" class="md-check access" name="<?php echo e($ch->id); ?>-v" <?php echo e(isset($dataau[$ch->id]) ? ($dataau[$ch->id]->view == 'y' ? 'checked' : '') : ''); ?> value="y">
                                <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_v">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td align="center">
                        <?php if($ch->create == 'y'): ?>
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_c" class="md-check access" name="<?php echo e($ch->id); ?>-c" <?php echo e(isset($dataau[$ch->id]) ? ($dataau[$ch->id]->create == 'y' ? 'checked' : '') : ''); ?> value="y">
                                <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_c">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td align="center">
                        <?php if($ch->edit == 'y'): ?>
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_e" class="md-check access" name="<?php echo e($ch->id); ?>-e" <?php echo e(isset($dataau[$ch->id]) ? ($dataau[$ch->id]->edit == 'y' ? 'checked' : '') : ''); ?> value="y">
                                <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_e">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td align="center">
                        <?php if($ch->delete == 'y'): ?>
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_d" class="md-check access" name="<?php echo e($ch->id); ?>-d" <?php echo e(isset($dataau[$ch->id]) ? ($dataau[$ch->id]->delete == 'y' ? 'checked' : '') : ''); ?> value="y">
                                <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_d">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td align="center">
                        <?php if($ch->sorting == 'y'): ?>
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_s" class="md-check access" name="<?php echo e($ch->id); ?>-s" <?php echo e(isset($dataau[$ch->id]) ? ($dataau[$ch->id]->sorting == 'y' ? 'checked' : '') : ''); ?> value="y">
                                <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_s">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td align="center">
                        <?php if($ch->export == 'y'): ?>
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_ex" class="md-check access" name="<?php echo e($ch->id); ?>-ex" <?php echo e(isset($dataau[$ch->id]) ? ($dataau[$ch->id]->export == 'y' ? 'checked' : '') : ''); ?> value="y">
                                <label for="checkbox_form_<?php echo e($pr->id); ?>_<?php echo e($ch->id); ?>_ex">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            <tbody>
          </table>
        </div>       
      </div>
      <div class="clearfix"></div><br/>
      <div class="box-footer">
        <?php echo view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'n']); ?>

      </div>
    </form>
  </div>
</div>
<?php if(isset($scripts)): ?>
  <?php $__env->startPush('scripts'); ?>
    <script>
      $(document).ready(function(){
        <?php echo $scripts; ?>

      });
    </script>
  <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
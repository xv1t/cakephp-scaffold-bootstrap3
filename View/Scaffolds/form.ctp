<?php
/**
 * Bootstrap Scaffold for CakePHP
 *
 * @author        Fedotov Victor (xv1t@yandex.ru)
 * @link          https://github.com/xv1t/cakephp-scaffold-bootstrap3
 * @package       Cake.View.Scaffolds
 * @version       0.1.2014-02-24
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php
$this->model = ClassRegistry::init($modelClass);
$simple_view = true;
$field_type = $this->model->getColumnTypes();

/*
  $scaffoldFields = array_flip($scaffoldFields);
  foreach ($scaffoldFields as &$v) {
  $v = array('empty' => '');
  }
 */
$action_name = ($this->request->action === 'add') ? 'Add' : 'Edit';
$button_name = ($this->request->action === 'add') ? 'Add' : 'Save';
?>
<div class="container">


    <div class="panel panel-default">

        <div class="panel-heading">
            <h3><?php echo Inflector::humanize($singularVar) ?>
                    <small><?php echo $action_name  ?></small>


                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php if ($this->request->action !== 'add'): ?>
                            <li><?php
                                echo $this->Form->postLink(
                                        __d('cake', 'Delete'), array('action' => 'delete', $this->Form->value($modelClass . '.' . $primaryKey)), null, __d('cake', 'Are you sure you want to delete # %s?', $this->Form->value($modelClass . '.' . $primaryKey)));
                                ?></li>
                        <?php endif; ?>
                        <li><?php echo $this->Html->link(__d('cake', 'List') . ' ' . $pluralHumanName, array('action' => 'index')); ?></li>
                        <?php
                        $done = array();
                        foreach ($associations as $_type => $_data) {
                            foreach ($_data as $_alias => $_details) {
                                if ($_details['controller'] != $this->name && !in_array($_details['controller'], $done)) {
                                    echo "\t\t<li>" . $this->Html->link(
                                            __d('cake', 'List %s', Inflector::humanize($_details['controller'])), array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'index')
                                    ) . "</li>\n";
                                    echo "\t\t<li>" . $this->Html->link(
                                            __d('cake', 'New %s', Inflector::humanize(Inflector::underscore($_alias))), array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'add')
                                    ) . "</li>\n";
                                    $done[] = $_details['controller'];
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </h3>
        </div>
        <div class="panel-body">
            <?php
            echo $this->Form->create(array(
                'class' => 'form-horizontal',
                'role' => 'form'
            ));
            ?>
            <?php
            echo $this->Form->create();
            foreach ($scaffoldFields as $scaffoldField) {
                if ($scaffoldField == $primaryKey) {
                    echo $this->Form->input($scaffoldField, array
                        (
                        'label' => false,
                        'div' => false,
                    ));
                } else {
                    if (!$simple_view || ($simple_view && !in_array($scaffoldField, array('created', 'modified', 'updated', 'create_user_id', 'update_user_id')))) {
                        $uid = uniqid('id');
                        $_opt = array
                            (
                            'label' => false,
                            'div' => false,
                            'id' => $uid,
                            'class' => "form-control",
                            'empty' => ''
                        );
                        if (!empty($this->request->query[$scaffoldField]))
                            $_opt['selected'] = $this->request->query[$scaffoldField];

                        $input = $this->Form->input($scaffoldField, $_opt);

                        if (!empty($field_type[$scaffoldField]))
                            if (in_array($field_type[$scaffoldField], array('date', 'datetime'))) {
                                $input = $this->Form->input($scaffoldField, array
                                    (
                                    'label' => false,
                                    'type' => 'text',
                                    'div' => false,
                                    'id' => $uid,
                                    'class' => "form-control"
                                ));
                                if ($field_type[$scaffoldField] == 'date')
                                    $input = str_replace('"text"', '"date"', $input);
                               // else
                                 //   $input = str_replace('"text"', '"datetime"', $input);
                            }
                        $caption = Inflector::humanize($scaffoldField);
                        ?>
                        <div class="form-group">
                            <label for="<?php echo $uid ?>" class="col-sm-2 control-label"><?php echo $caption ?></label>
                            <div class="col-sm-10">
                                <?php echo $input ?>
                            </div>
                        </div>   
                        <?php
                    }//
                }
            }//foreach
            ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-primary btn-lg" value="<?php echo $button_name ?>">
                </div>
            </div>
            <?php
            echo $this->Form->end();
            ?>  
        </div>
    </div>
</div>

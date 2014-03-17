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
$field_type = $this->model->getColumnTypes();

if (!isset($modules)) {
    $modulus = 11;
}
if (!isset($model)) {
    // $models = ClassRegistry::keys();
    //  $model = Inflector::camelize(current($models));
    $model = $modelClass;
}


$simple_view = true;
$table_fields = $scaffoldFields;
if ($simple_view) {
    /*
     * hide
     */
    for ($i = count($table_fields) - 1; $i >= 0; $i--) {
        if (in_array($table_fields[$i], array('id', 'created', 'create_user_id', 'update_user_id', 'updated')))
            unset($table_fields[$i]);
    }
}

//debug($scaffoldFields);
?>

<div class="col-sm-12">



    <div class="panel panel-default">
        <div class="panel-heading"><h3><span class="glyphicon glyphicon-list-alt"></span> <?php echo $pluralHumanName; ?>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>   
                            <?php echo $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-plus-sign"></span> Create %s', $singularHumanName), array('action' => 'add'), array('escape' => false)); ?>
                        </li>
                        <?php
                        $done = array();
                        foreach ($associations as $_type => $_data) {
                            foreach ($_data as $_alias => $_details) {
                                if ($_details['controller'] != $this->name && !in_array($_details['controller'], $done)) {

                                    echo '<li class="divider"></li>';
                                    echo '<li>';
                                    echo $this->Html->link(
                                            __d('cake', '<span class="glyphicon glyphicon-list-alt"></span> List %s', Inflector::humanize($_details['controller'])), array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'index'), array('escape' => false)
                                    );
                                    echo '</li>';
                                    echo '<li>';
                                    echo $this->Html->link(
                                            __d('cake', '<span class="glyphicon glyphicon-plus-sign"></span> Create %s', Inflector::humanize(Inflector::underscore($_alias))), array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'add'), array('escape' => false)
                                    );
                                    echo '</li>';
                                    $done[] = $_details['controller'];
                                }
                            }
                        }
                        ?>

                    </ul>
                </div>
            </h3></div>

        <div style="overflow-x: auto">


            <table class="table table-bordered table-hover table-condensed" >
                <thead>
                    <tr class="active">
                        <?php foreach ($table_fields as $_field): ?>
                            <th><?php echo $this->Paginator->sort($_field); ?></th>
                        <?php endforeach; ?>
                        <th><?php echo __d('cake', 'Actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (${$pluralVar} as ${$singularVar}):
                        echo '<tr>';
                        foreach ($table_fields as $_field) {
                            $isKey = false;
                            if (!empty($associations['belongsTo'])) {
                                foreach ($associations['belongsTo'] as $_alias => $_details) {
                                    if ($_field === $_details['foreignKey']) {
                                        $isKey = true;
                                        echo '<td>' . $this->Html->link(${$singularVar}[$_alias][$_details['displayField']], array('controller' => $_details['controller'], 'action' => 'view', ${$singularVar}[$_alias][$_details['primaryKey']])) . '</td>';
                                        break;
                                    }
                                }
                            }
                            if ($isKey !== true) {
                                $td_val = '';
                                if (empty(${$singularVar}))
                                    $td_val = '';
                                else {
                                    if (!empty(${$singularVar}[$modelClass]))
                                        if (!empty(${$singularVar}[$modelClass][$_field]))
                                            $td_val = h(${$singularVar}[$modelClass][$_field]);
                                }
                                /*
                                 * Boolean=checkbox field
                                 */

                                if (
                                        strpos($_field, 'url') !== false &&
                                        strpos(${$singularVar}[$modelClass][$_field], 'http://') === 0
                                ) {
                                    $td_val = "<a onclick='if (!confirm(\"Go??\")) return false;' href=\"$td_val\" target=\"about_blank\">$td_val</a>";
                                    /*
                                     * generate link
                                     */
                                }

                                $td_options = null;
                                if ($field_type[$_field] == 'boolean') {
                                    $td_options = ' class=text-center';
                                    $td_val = '<input disabled type=checkbox ';
                                    if (${$singularVar}[$modelClass][$_field])
                                        $td_val .= 'checked';
                                    $td_val .= '>';
                                }
                                echo "<td$td_options>" . $td_val . '</td>';
                            }
                        } //field

                        echo '<td class="noactions"><nobr>';
                        echo $this->Html->link('<span class="glyphicon glyphicon-eye-open"><span>', array('action' => 'view', ${$singularVar}[$modelClass][$primaryKey],), array('escape' => false, 'class' => 'btn btn-default btn-xs'));
                        echo ' ' . $this->Html->link('<span class="glyphicon glyphicon-pencil"><span>', array('action' => 'edit', ${$singularVar}[$modelClass][$primaryKey]), array('escape' => false, 'class' => 'btn btn-default btn-xs'));
                        echo ' ' . $this->Form->postLink(
                                '<span class="glyphicon glyphicon glyphicon-remove"><span>', array('action' => 'delete', ${$singularVar}[$modelClass][$primaryKey]), array('escape' => false, 'class' => 'btn btn-default btn-xs'), __d('cake', 'Are you sure you want to delete # %s?', ${$singularVar}[$modelClass][$primaryKey])
                        );
                        echo '</nobr></td>';
                        echo '</tr>';

                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <p><?php
                    echo $this->Paginator->counter(array(
                        'format' => __d('cake', 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                    ));
                    ?></p>
            <ul class="pagination  pagination-sm">

<?php echo $this->Paginator->first('<span class="glyphicon glyphicon-step-backward"></span>&nbsp;', array('tag' => 'li', 'title' => 'First', 'escape' => false)); ?>
                <?php
                echo $this->Paginator->prev('<span class="glyphicon glyphicon-backward"></span>&nbsp;', array(
                    'tag' => 'li',
                    'escape' => false,
                    'title' => 'Prevous page',
                    'class' => 'prev',
                        ), $this->Paginator->link('<span class="glyphicon glyphicon-backward"></span>&nbsp;', array(), array(
                            'escape' => false,
                        )), array(
                    'tag' => 'li',
                    'escape' => false,
                    'class' => 'prev disabled',
                ));
                ?>
                <?php
                // debug($model);
                //  debug($this->params['paging']);

                $page = $this->params['paging'][$model]['page'];
                $pageCount = $this->params['paging'][$model]['pageCount'];
                if ($modulus > $pageCount) {
                    $modulus = $pageCount;
                }
                $start = $page - intval($modulus / 2);
                if ($start < 1) {
                    $start = 1;
                }
                $end = $start + $modulus;
                if ($end > $pageCount) {
                    $end = $pageCount + 1;
                    $start = $end - $modulus;
                }
                for ($i = $start; $i < $end; $i++) {
                    $url = array('page' => $i);
                    $class = null;
                    if ($i == $page) {
                        $url = array();
                        $class = 'active';
                    }
                    echo $this->Html->tag('li', $this->Paginator->link($i, $url), array(
                        'class' => $class,
                    ));
                }
                echo $this->Paginator->next('<span class="glyphicon glyphicon-forward"></span>&nbsp;', array(
                    'tag' => 'li',
                    'class' => 'next',
                    'escape' => false,
                    'title' => 'Next page',
                        ), $this->Paginator->link('<span class="glyphicon glyphicon-forward"></span>&nbsp;', array(), array('escape' => false,)), array(
                    'tag' => 'li',
                    'escape' => false,
                    'class' => 'next disabled',
                ));
                ?>
                <?php
                echo $this->Paginator->last('<span class="glyphicon glyphicon-step-forward"></span>&nbsp;', array('tag' => 'li', 'title' => 'Last', 'escape' => false,));
                ?>

            </ul>


        </div>
    </div>
</div>
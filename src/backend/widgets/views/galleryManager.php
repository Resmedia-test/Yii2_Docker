<?php
/**
 * @var $this View
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
use yii\helpers\Html;
use yii\web\View;

?>
<?php echo Html::beginTag('div', $this->context->options); ?>
<!-- Gallery Toolbar -->
<i title="Запрещено загружать фото размерами менее чем (600 х 328px). Первая фотография явлется основной. Фото можно переставлять, зажав и перетащив его на необходимое место." class="question glyphicon glyphicon-question-sign"></i>
<label>Фотографии</label>
<div class="hint-block">Фото загружается после сохранения материала</div>
<div class="btn-toolbar" style="padding:4px">
    <div class="btn-group" style="display: inline-block;">
        <div class="btn btn-success btn-file" style="display: inline-block">
            <i class="glyphicon glyphicon-plus"></i>Добавить
            <input type="file" name="image" class="afile" accept="image/*" multiple="multiple"/>
        </div>
    </div>
    <div class="btn-group" style="display: inline-block;">

        <label class="btn btn-default">
            <input type="checkbox" style="margin-right: 4px;" class="select_all">Выбрать все
        </label>
        <div class="btn btn-default disabled edit_selected">
            <i class="glyphicon glyphicon-pencil"></i> Изменить
        </div>
        <div class="btn btn-default disabled remove_selected">
            <i class="glyphicon glyphicon-remove"></i> Удалить
        </div>
    </div>
</div>

<hr/>
<!-- Gallery Photos -->
<div class="sorter">
    <div class="images"></div>
    <br style="clear: both;"/>
</div>

<!-- Modal window to edit photo information -->
<div class="editor-modal modal fade" id="file-name-form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>

                <h3 class="modal-title">Редактировать информацию</h3>
            </div>
            <div class="modal-body">
                <div class="form"></div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary save-changes">
                    Сохранить
                </a>
                <a href="#" class="btn close-upload" onClick="$('#file-name-form').modal('hide')">Закрыть</a>
            </div>
        </div>
    </div>
</div>

<div class="overlay">
    <div class="overlay-bg">&nbsp;</div>
    <div class="drop-hint">
        <span class="drop-hint-info">Перетащить сюда файлы</span>
    </div>
</div>

<div class="progress-overlay">
    <div class="overlay-bg">&nbsp;</div>
    <!-- Upload Progress Modal-->
    <div class="modal progress-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Загрузка изображений</h3>
                </div>
                <div class="modal-body">
                    <div class="progress ">
                        <div class="progress-bar progress-bar-info progress-bar-striped active upload-progress"
                             role="progressbar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo Html::endTag('div'); ?>

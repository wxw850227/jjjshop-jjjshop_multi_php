<?php

namespace app\supplier\controller\file;

use app\supplier\controller\Controller;
use app\shop\model\file\UploadFile as UploadFileModel;
use app\common\model\file\UploadGroup as UploadGroupModel;

class File extends Controller
{
    /**
     * 文件库列表
     */
    public function lists($pageSize, $type = 'image', $group_id = -1)
    {
        // 文件列表
        $file_list = (new UploadFileModel)->getlist(intval($group_id), $type, $pageSize, -1, $this->getSupplierId());
        return $this->renderSuccess('success', compact('file_list'));
    }
	
	/**
	 * 类别列表
	 */
	public function category($type = 'image')
	{
	    // 分组列表
	    $group_list = (new UploadGroupModel)->getList($type, $this->getSupplierId());
	    return $this->renderSuccess('success', compact('group_list'));
	}

    /**
     * 新增分组
     */
    public function addGroup($group_name, $group_type = 'image')
    {
        $model = new UploadGroupModel;
        $shop_supplier_id = $this->getSupplierId();
        if ($model->add(compact('group_name', 'group_type', 'shop_supplier_id'))) {
            $group_id = $model->getLastInsID();
            return $this->renderSuccess('添加成功',  compact('group_id', 'group_name'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑分组
     */
    public function editGroup($group_id, $group_name)
    {
        $model = UploadGroupModel::detail($group_id, $this->getSupplierId());
        if ($model->edit(compact('group_name'))) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 删除分组
     */
    public function deleteGroup($group_id)
    {
        $model = UploadGroupModel::detail($group_id, $this->getSupplierId());
        if ($model->remove()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    /**
     * 批量删除文件
     */
    public function deleteFiles($fileIds)
    {
        $model = new UploadFileModel;
        if ($model->softDelete($fileIds)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }


    /**
     * 批量移动文件分组
     */
    public function moveFiles($group_id, $fileIds)
    {
        $model = new UploadFileModel;
        if ($model->moveGroup($group_id, $fileIds) !== false) {
            return $this->renderSuccess('移动成功');
        }
        return $this->renderError($model->getError() ?: '移动失败');
    }
}

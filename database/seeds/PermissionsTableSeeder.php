<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function data() : array
    {
        return [
            ['viewLevelOneCheckSet', '查看一级检查夹', '可以查看一级检查夹的数据'],
            ['addLevelOneCheckSet', '添加一级检查夹', '可以新建新的一级检查夹'],
            ['deleteLevelOneCheckSet', '删除一级检查夹', '可以删除一级检查夹'],

            ['viewLevelTwoCheckSet', '查看二级检查夹', '可以查看二级检查夹的数据'],
            ['addLevelTwoCheckSet', '添加二级检查夹', '可以新建二级检查夹'],
            ['deleteLevelTwoCheckSet', '删除二级检查夹', '可以删除二级检查夹'],

            ['viewCheckTask', '查看检查', '可以查看检查数据'],
            ['editCheckTask', '编辑检查', '可以编辑检查数据'],
            ['addCheckTask', '添加检查', '可以新建一个检查'],
            ['deleteCheckTask', '删除检查', '可以删除一个检查'],
            ['changeStateOfCheckTask', '切换检查状态', '可以切换一个检查的状态'],

            ['viewBuilding', '查看楼栋', '可以查看楼栋相关信息'],
            ['editBuilding', '编辑楼栋', '可以编辑楼栋相关信息'],
            ['addBuilding', '添加楼栋', '可以新建一个楼栋'],
            ['deleteBuilding', '删除楼栋', '可以删除一个楼栋'],
            ['activeBuilding', '激活楼栋', '可以激活一个楼栋'],

            ['viewDormitory', '查看宿舍', '可以查看宿舍相关信息'],
            ['editDormitory', '编辑宿舍', '可以编辑宿舍相关信息'],
            ['addDormitory', '添加宿舍', '可以新建一个宿舍'],
            ['deleteDormitory', '删除宿舍', '可以删除一个宿舍'],
            ['activeDormitory', '激活宿舍', '可以激活一个宿舍'],
            ['importDormitory', '导入宿舍', '可以导入宿舍'],

            ['viewItem', '查看项目', '可以查看项目相关信息'],
            ['editItem', '编辑项目', '可以编辑项目相关信息'],
            ['addItem', '添加项目', '可以新建一个项目'],
            ['deleteItem', '删除项目', '可以删除一个项目'],

            ['viewSelf', '查看个人信息', '可以查看与自己有关的信息'],
            ['editSelfStudentId', '编辑个人学号', '可以编辑自己的学号'],
            ['editSelfName', '编辑个人姓名', '可以编辑自己的姓名'],
            ['editSelfEmail', '编辑个人邮箱', '可以编辑自己的邮箱'],

            ['viewMembers', '查看干事', '可以查看干事相关信息'],
            ['editMembersStudentId', '编辑干事学号', '可以编辑干事的学号'],
            ['editMemberName', '编辑干事姓名', '可以编辑干事的姓名'],
            ['editMemberEmail', '编辑干事邮箱', '可以编辑干事的邮箱'],
            ['addMember', '添加干事', '可以添加一个干事'],
            ['deleteMember', '删除干事', '可以删除干事'],
            ['activeMember', '激活干事', '可以激活干事'],
            ['importMember', '导入干事', '可以导入干事列表'],

            ['viewViceLeader', '查看副部', '可以查看副部相关信息'],
            ['editViceLeaderStudentId', '编辑副部学号', '可以编辑副部的学号'],
            ['editViceLeaderName', '编辑副部姓名', '可以编辑副部的姓名'],
            ['editViceLeaderEmail', '编辑副部邮箱', '可以编辑副部的邮箱'],
            ['addViceLeader', '添加副部', '可以添加一个副部'],
            ['deleteViceLeader', '删除副部', '可以删除副部'],
            ['activeViceLeader', '激活副部', '可以激活副部'],

            ['viewLeader', '查看部长', '可以查看部长相关信息'],
            ['editLeaderStudentId', '编辑部长学号', '可以编辑部长的学号'],
            ['editLeaderName', '编辑部长姓名', '可以编辑部长的姓名'],
            ['editLeaderEmail', '编辑部长邮箱', '可以编辑部长的邮箱'],

            ['viewAnnouncement', '查看公告', '可以查看系统公告'],
            ['editAnnouncement', '编辑公告', '可以编辑系统公告'],

            ['viewTimeline', '查看时间线', '可以查看时间线'],
            ['editTimeline', '编辑时间线', '可以编辑时间线'],
            ['addTimeline', '添加时间线', '可以添加时间线'],
            ['deleteTimeline', '删除时间线', '可以删除时间线']
        ];
    }

    public function run(\App\Models\Permission $permission)
    {
        foreach ($this->data() as $datum)
        {
            $p = clone $permission;
            $p->english_name = $datum[0];
            $p->chinese_name = $datum[1];
            $p->description = $datum[2];
            $p->save();
        }
    }
}

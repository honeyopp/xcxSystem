<body>
    <div class="page-content clearfix">
        <div id="Member_Ratings">
            <div class="d_Confirm_Order_style">
                               
                <div class="search_style">
                    <div class="title_names">搜索查询</div>
                    <form method="post" action="<?=url('demo.demo/index')?>">
                    <ul class="search_content clearfix">
                       <li><label class="l_f">标题</label><input name="title" id="title"  value="<?=$search['title']?>" type="text"  class="text_add" placeholder="请输入标题"  style=" width:200px"/></li>
<li><label class="l_f">分类ID</label><input name="cat_id" id="cat_id"  value="<?=$search['cat_id']?>" type="text"  class="text_add" placeholder="请输入分类ID"  style=" width:200px"/></li>

                        <li style="width:90px;"><button type="submit" class="btn_search"><i class="icon-search"></i>查询</button></li>
                    </ul>
                    </form>
                </div>
                <!---->
                <div class="border clearfix">
        <span class="l_f">
           <a title="添加DEMO" mini="load" w="100%" h="100%" href="<?=url('demo.demo/create')?>" id="member_add" class="btn btn-warning"><i class="icon-plus"></i>添加DEMO</a>
           <a mini="list" for="mini_list" title="批量删除DEMO" href="<?=url('demo.demo/delete')?>" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>
        </span>
        <span class="r_f">共：<b><?=$totalNum?></b>条</span>
</div>
                <!---->
                <div class="table_menu_list">
                    <table class="table table-striped table-bordered table-hover" id="sample-table">
                        <thead>
                            <tr>
                                <th width="25"><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                                <th>ID</th>
                                <th>标题</th>
<th>排序</th>
<th>图片</th>
<th>图片组</th>
<th>详情</th>
<th>分类ID</th>
<th>是否显示</th>
<th>是否首页</th>
<th>创建时间</th>
<th>创建IP</th>
              
                                <th >操作</th>
                            </tr>
                        </thead>
                          <form  id="mini_list">
                        <tbody >
                            <?php foreach($list as $val){?>
                            <tr>
                                <td><label><input type="checkbox" id="demo_id_<?=$val->demo_id?>" name="demo_id[]" value="<?=$val->demo_id?>" class="ace"><span class="lbl"></span></label></td>
                                <td><?=$val->demo_id?></td>
                                <td><?=$val->title?></td>
<td><?=$val->orderby?></td>
<td><?=$val->photo?></td>
<td><?=$val->photos?></td>
<td><?=$val->details?></td>
<td><?=$val->cat_id?></td>
<td><?=$val->is_show?></td>
<td><?=$val->is_index?></td>
<td><?=$val->add_time?></td>
<td><?=$val->add_ip?></td>

                                <td class="td-manage" style=" text-align: left;">
                                    <a title="编辑DEMO" mini="load" w="100%" h="100%" href="<?=url('demo.demo/edit','demo_id='.$val->demo_id)?>"  class="btn btn-xs btn-info" ><i class="icon-edit bigger-120"></i></a>
                                    <a title="删除DEMO" mini="act"  href="<?=url('demo.demo/delete','demo_id='.$val->demo_id)?>"  class="btn btn-xs btn-warning"><i class="icon-trash bigger-120"></i></a>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                          </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row"><div class="col-sm-6"><?=$page?></div></div>
    <!--添加用户图层-->
</body>
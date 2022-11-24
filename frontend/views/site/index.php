<?php

/* @var $this yii\web\View */

$this->title = 'java 代码生成支持';

$aryConn = [];
if($conns){
    foreach ($conns as $one){
        $aryConn[$one] = $one;
    }
}

function getJavaType($t){
    if(stristr($t,'char') || stristr($t,'text')){
        return 'String';
    }
    if(stristr($t,'int')){
        return 'Integer';
    }
    if(stristr($t,'datetime')){
        return 'Date';
    }
    return $t;
}

function getCamName($name){
    return str_replace(' ','',ucwords(str_replace('_',' ',$name)));
}

$packagename = isset($_GET['packagename']) ? $_GET['packagename'] : 'io.renren.modules.gem';
?>
<div class="site-index">

    <div>
        <h1>java mybatis-plus sql server 代码生成支持</h1>

        <?php
        $form = \yii\bootstrap\ActiveForm::begin();
        ?>
        <?php
        if($aryConn){
        ?>
            <script>
                function c(t) {
                    $.getJSON('<?= \common\helpers\Url::to(['getconn']) ?>?dsn=' + $(t).val(),{},function (res) {
                        console.log(res)
                        $('#ipandport').val(res.ipandport)
                        $('#dbname').val(res.dbname)
                        $('#username').val(res.username)
                        $('#password').val(res.password)
                    });
                }
                </script>
        <div class="form-group">
            <label class="form-control">常用数据库<a href="<?= \common\helpers\Url::to(['clear']) ?>">清空</a></label>
            <?=\common\helpers\Html::dropDownList("conns","",$aryConn,[
                    'text' => '选择常用数据库',
                    'onchange' => 'c(this)',
                    'class' => 'form-control'
                ]) ?>
        </div>
        <?php
        }
        ?>

        <?= $form->field($model,'ipandport')->hint("IP,PORT 以逗号隔开")->textInput([
            'id' => 'ipandport'
        ]) ?>
        <?= $form->field($model,'dbname' )->textInput([
            'id' => 'dbname'
        ])  ?>
        <?= $form->field($model,'username' )->textInput([
            'id' => 'username'
        ])  ?>
        <?= $form->field($model,'password' )->textInput([
            'id' => 'password'
        ])  ?>
        <?= \yii\bootstrap\Html::submitButton('连接', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <script>
                    function go(tablename) {
                        location = "?table="+tablename+"&packagename="+$('#packagename').val();
                    }
                </script>
                <h2>表</h2>
                <div>java 父包名：<input type="text" class="text" id="packagename" value="<?= $packagename ?>"/> </div>
                <ul>
                    <?php
                    foreach ($tables as $one){
                    ?>
                   <li> <a onclick="go('<?= $one ?>')" href="javascript:"><?= $one ?></a> </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="col-lg-8">
                <?php
                if($resultFields){
                ?>
                <h2>代码</h2>

                <h4>模型</h4>
                <pre>
package <?= $packagename ?>.entity;

import com.baomidou.mybatisplus.annotations.TableField;
import com.baomidou.mybatisplus.annotations.TableId;
import com.baomidou.mybatisplus.annotations.TableName;
import com.baomidou.mybatisplus.enums.IdType;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;


@Data
@TableName("<?=$tableName ?>")
@NoArgsConstructor
@AllArgsConstructor
public class <?=getCamName($tableName) ?>Entity implements Serializable {
    private static final long serialVersionUID = 1L;


    @TableId(value = "UserID")
    private Integer UserID;
<?php
foreach ($resultFields as $row){
?>
    /**
    * <?= $row['字段说明'] ?>
    * 类型： <?= $row['类型'] ?>  长度：<?= $row['长度'] ?>   小数位数:<?= $row['小数位数'] ?> 可空:<?= $row['允许空'] ?>
    */
    <?php if($row['标识']){ ?>
        @TableId(value = "<?= $row['字段名'] ?>",type = IdType.AUTO)
        <?php }else{ ?>
    @TableField("<?= $row['字段名'] ?>")
        <?php } ?>
    private <?= $row['标识'] ? 'Long' : getJavaType($row['类型']) ?> <?= str_replace(' ','',ucwords(str_replace('_',' ',$row['字段名']))) ?>;
                    <?php
    echo PHP_EOL;
                    }
?>

}

                </pre>
                <hr>

                <h4>DAO</h4>
                <pre>

package <?= $packagename ?>.dao;

import com.baomidou.mybatisplus.mapper.BaseMapper;
import io.renren.modules.gem.entity.UserAppkeyEntity;
import org.apache.ibatis.annotations.Mapper;

@Mapper
public interface <?=getCamName($tableName) ?>Dao extends BaseMapper&lt;<?=getCamName($tableName) ?>Entity> {

}
                </pre>
                <hr>

                <h4>Service</h4>
                <pre>
package <?= $packagename ?>.service;

import com.baomidou.mybatisplus.service.IService;
import io.renren.common.utils.PageUtils;
import io.renren.modules.gem.entity.ChannelInfoAccEntity;
import io.renren.modules.gem.entity.UserAppkeyEntity;

import java.io.Serializable;
import java.util.Collection;
import java.util.Map;


public interface <?=getCamName($tableName) ?>Service extends IService&lt;<?=getCamName($tableName) ?>Entity> {

    PageUtils queryPage(Map<String, Object> params);

    UserAppkeyEntity getEntityById(Integer id);

    boolean saveEntity(UserAppkeyEntity entity);

    boolean updateEntityById(UserAppkeyEntity entity);

    boolean removeEntityBatchIds(Collection&lt;? extends Serializable> idList);

}


                </pre>
                <hr>

                <h4>Service Imp</h4>
                <pre>
package <?= $packagename ?>.service.impl;

import com.baomidou.mybatisplus.mapper.EntityWrapper;
import com.baomidou.mybatisplus.plugins.Page;
import com.baomidou.mybatisplus.service.impl.ServiceImpl;
import io.renren.common.utils.PageUtils;
import io.renren.common.utils.Query;
import io.renren.datasources.DataSourceNames;
import io.renren.datasources.annotation.DataSource;
import io.renren.modules.gem.dao.ChannelInfoAccDao;
import io.renren.modules.gem.dao.UserAppkeyDao;
import io.renren.modules.gem.entity.ChannelInfoAccEntity;
import io.renren.modules.gem.entity.UserAppkeyEntity;
import io.renren.modules.gem.service.ChannelInfoAccService;
import io.renren.modules.gem.service.UserAppkeyService;
import org.apache.commons.lang.StringUtils;
import org.springframework.stereotype.Service;

import java.io.Serializable;
import java.util.Collection;
import java.util.Date;
import java.util.Map;


@Service("<?= lcfirst(getCamName($tableName)) ?>Service")
public class <?=getCamName($tableName) ?>ServiceImpl extends ServiceImpl&lt;<?=getCamName($tableName) ?>Dao, <?=getCamName($tableName) ?>Entity> implements <?=getCamName($tableName) ?>Service {

    @Override
    @DataSource(name = DataSourceNames.ACCOUNTS)
    public PageUtils queryPage(Map&lt;String, Object> params) {

        Wrapper&lt;<?=getCamName($tableName) ?>Entity> wrapper = new EntityWrapper&lt;<?=getCamName($tableName) ?>Entity>();
        Page&lt;<?=getCamName($tableName) ?>Entity> page = this.selectPage(
                new Query&lt;<?=getCamName($tableName) ?>Entity>(params).getPage(),
                wrapper
        );

        return new PageUtils(page);
    }

    @Override
    @DataSource(name = DataSourceNames.ACCOUNTS)
    public <?=getCamName($tableName) ?>Entity getEntityById(Integer id) {
        return selectById(id);
    }

    @Override
    @DataSource(name = DataSourceNames.ACCOUNTS)
    public boolean saveEntity(<?=getCamName($tableName) ?>Entity entity) {
        return insert(entity);
    }

    @Override
    @DataSource(name = DataSourceNames.ACCOUNTS)
    public boolean updateEntityById(<?=getCamName($tableName) ?>Entity entity) {
        return updateById(entity);
    }

    @Override
    @DataSource(name = DataSourceNames.ACCOUNTS)
    public boolean removeEntityBatchIds(Collection&lt;? extends Serializable> idList) {
        return deleteBatchIds(idList);
    }

}
                </pre>

                <?php
                }
                ?>
            </div>
        </div>

    </div>
</div>

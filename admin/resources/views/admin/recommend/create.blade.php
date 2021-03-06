<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <link href="https://cdn.bootcss.com/wangEditor/10.0.13/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/10.0.13/wangEditor.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <style>
  .layui-upload-img{
      width:330px;
      height:80px;
      margin: 0 10px 10px 0;
  }
  </style>
  <body>
    <div class="x-body">
        <form class="layui-form" id="update" enctype="application/x-www-form-urlencoded">
          <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>推荐标题
                </label>
                <div class="layui-input-block">
                    <input type="text" name="title" lay-verify="title|required" value=""  autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
          </div>
          <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>推荐地址
                </label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="url|required" value=""  autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
          </div>
          <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>是否显示
                </label>
                <div class="layui-input-block">
                        <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否">
                </div>
          </div>
          <div class="layui-form-item">
                <label for="L_username" class="layui-form-label">
                    <span class="x-red">*</span>封面
                </label>
                <div class="layui-input-inline">
                      <div class="layui-upload">
                      <button type="button" class="layui-btn" id="test1">上传图片</button>
                      <div class="layui-upload-list">
                          <img class="layui-upload-img" id="demo1">
                          <p id="demoText"></p>
                      </div>
                      </div>   
                </div>
          </div>
          @csrf
          <input type="hidden" name="cover" id="cover">
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="add" lay-submit="">
                  添加
              </button>
          </div>
      </form>
    </div>
    <script>
    //内容编辑器
      var E = window.wangEditor
      var editor = new E('#editor')
      // 或者 var editor = new E( document.getElementById('editor') )
      editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $("textarea[name=content]").val(html)
      }
      editor.create()
      editor.txt.html($("textarea[name=content]").val());

      //layui
      layui.use(['form','layer','upload'], function(){
          $ = layui.jquery;
        var form = layui.form
        ,layer = layui.layer
        ,upload = layui.upload;

        //上传图片配置
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: "{{route('upload_image',['filename'=>'cover'])}}"
            ,data: {
                _token:"{{csrf_token()}}"
            }
            ,accept:"images"
            ,field:"cover"
            ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('#demo1').attr('src', result); //图片链接（base64）
            });
            }
            ,done: function(res,index,upload){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功
                // console.log(res.path);
                // console.log($("input[name=cover]"));
                $("#cover").val(res.path);
            }
            ,error: function(index,upload){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
        //监听提交
        form.on('submit(add)', function(data){
            if($("#cover").val()==""){
                layer.msg('封面图片不能为空!', {icon: 5}); 
                return false;
            }

          //发异步，把数据提交给php
          $.ajax({
              url:"{{  route('recommend.store')  }}",
              type:"post",
              data:$("#update").serialize(),
              success:function(data){
                  console.log(data);
              }
          })
          layer.alert("增加成功", {icon: 6},function () {
              // 获得frame索引
              var index = parent.layer.getFrameIndex(window.name);
            //   console.log(index);
              //关闭当前frame
              parent.layer.close(index);
          });
          return false;
        });
        
        
      });
  </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>

</html>
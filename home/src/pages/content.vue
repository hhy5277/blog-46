<template>
  <div>
      

      <header>

          <a href="javascript:;"  id='toogleAside'><i class="layui-icon layui-icon-spread-left" style="color:#fff;font-size:20px;"></i></a>

        <a href="" @click.prevent="search()"><i class="layui-icon layui-icon-search" style="color:#fff;font-size:20px;"></i></a>

      </header>
      
      <banner @resize="resize"></banner>
      <!-- ,backgroundColor:'#f7f7f7' -->
      
      <div  class="layui-fluid box" ref="box" :style="{marginTop:bannerMargin,paddingTop:'20px',minHeight:'700px'}">
        <loding  v-show="$store.state.isLoding"></loding>

        <div class="layui-row layui-col-space30">
            <div class="layui-col-xs12 layui-col-sm12 layui-col-md11 layui-col-lg7 layui-col-lg-offset1">
               
               <div class="layui-row">
                  <div class="layui-col-xs12 layui-col-sm12 layui-col-md12 layui-col-lg10 layui-col-lg-offset2 container-box">
                      <!-- 通知:超小屏幕,小屏幕,大屏幕显示  -->

                       <div class="content-box markdown-body">
                            <fieldset class="layui-elem-field">
                                <legend style="" class="title" >{{data.title}}</legend>
                            
                                <p v-html="data.preface"></p>
                                <hr class="layui-bg-green">
                                <div class="layui-field-box content" v-html="data.content" v-highlight></div>
                            </fieldset>
                       </div>                            
                      <footer  v-show="$store.state.navActive!='index'" class="footer">
                        <p class="reprint">转载声明：商业转载请联系作者获得授权,非商业转载请注明出处 © Hcc</p>
                        Copyright © 2018&nbsp;-&nbsp; Hcc&nbsp;&nbsp;鄂ICP备17027437号
                        
                          &nbsp;-&nbsp;<a href="/">52qy.top</a>

                      </footer>
                  </div>

              </div>
            </div>
            <!-- 通知:超大屏显示 -->
            <div class="layui-col-lg3  layui-hide-xs layui-hide-sm layui-hide-md layui-show-lg-block">
                <right></right>
            </div>
        </div>
      </div> 

  </div>
</template>

<script>
import banner from "@/components/banner"
import loding from "@/components/loding"
import right from "@/components/right"

import obj from "@/axios/api"



export default {
  name: 'async',
  components:{
    banner,
    right,
    loding,
  },
  metaInfo() {
      return {
        title: this.title+" - Hcc的个人博客", // set a title
        meta: [
            {                 // set meta
                name: 'keywords',
                content: "PHP,Laravel,VUE,jQuery,CSS,HTML,MYSQL,redis,LNMP,Bootstrap,AmazeUI前端,Hcc的个人博客"
            },
            {
                name: 'description',
                content: this.description
            }
        ]
      }
  },
  data(){
    return {
      num: null,
      data:[],
      layer:"",
      banner:"",
      bannerMargin:"1px",
      clientHeight:document.documentElement.clientHeight,
      description:"",
      title:"",
    }
  },
  mounted:function(){
      var that = this;
      this.$store.commit('updateNavActive',"category");
      
      //获取文章内容
      obj.getContent(this.$route.params.id).then((res)=>{
          this.data = res.data;
          // document.title = res.data.title+" -Hcc个人博客";
          this.title = res.data.title;
          this.description = res.data.preface;
          this.$store.commit("updateCateActive",this.data.category_id)

          
      })

      layui.use("layer",function(){      
        that.layer = layui.layer;
      })
  },
  methods:{
    search:function(){

      this.layer.open({
        type:1,
        title:"搜索",
        content: `<div class="search-box layui-fluid">
        <div class="layui-row">
          <div class="layui-col-xs10 layui-col-sm10 layui-col-md11 layui-col-lg11">
            <input type="text" name="title" required lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">  
          </div>
          <div class="layui-col-xs2 layui-col-sm2 layui-col-md1 layui-col-lg1">
            <button class="layui-btn layui-btn-radius layui-btn-normal">搜索</button>
          </div>
        </div>
      </div>`, 
        // skin:"layui-layer-lan",
        area:['50%','90px']
      });
      
    },

    resize:function(value){
      
      this.bannerMargin = value;
    },
  }
 
}
</script>

<style>
body {
  background-color: #eee;
}
.container-box {
    border-radius: 5px;
    background-color:#fff;
    padding:60px 20px 0px;
    min-height:700px;
    overflow: hidden;
    /* position:absolute; */
}
.container-box>:first-child {
  margin-bottom:130px;
}
header {
  box-sizing: border-box;
  width:100%;
  padding:0 40px;
  line-height: 60px;
  position:absolute;
  top:0px;
  z-index: 999;
  text-align:justify;
  text-align-last:justify;
}
.header i {
  z-index:2;
}

.box .layui-row{
  height:100%;
}

/*搜索*/
.search-box input {
  border-radius: 20px;
}
.reprint {
  margin-bottom:10px;
}
.footer {
  width: 100%;
  position:absolute;
  bottom:25px;
  left:0px;
  line-height: 22px;
  text-align: center;
}
/* 遮盖 */
.zhegai {
  width: 100%;
  height:100%;
  position: fixed;
  top:0px;
  left:0px;
  background: rgba(0,0,0,.2);
  z-index:5;
}


.content-box .title {
    color:#666;
    font-size:25px;
    font-weight:bold;
 }
 .content-box.content {
    font-size:16px;
    margin-top:20px;
 }
.content-box .layui-elem-field legend {
  font-size: 28px;
    font-weight: bold;
    color:#333;
}


/*markdown样式*/
html { font-size: 100%; overflow-y: scroll; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }

body{
    color:#444;
    font-size:13px;
    line-height:1.5em;
    margin:auto;
}
.content-box {
    font-family: -apple-system,BlinkMacSystemFont,"Lucida Grande","Helvetica Neue",Helvetica,Arial,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei","Noto Sans CJK SC","Source Han Sans CN",SimSun,sans-serif;

}
h1, h2, h3, h4, h5, h6 {
    font-weight: bold;
}
.content-box h1,.content-box h2,.content-box h3,.content-box h4,.content-box h5,.content-box h6{
  margin-top:15px;
}
 .content-box h1 {
    color: #000000;
    font-size: 28px;
}

 .content-box h2 {
    border-bottom: 2px solid #CCCCCC;
    color: #000000;
    font-size: 24px;
}

 .content-box h3 {
    border-bottom: 2px solid #CCCCCC;
    font-size: 18px;
}

h4 {
    font-size: 16px;
}

h5 {
    font-size: 14px;
}

h6 {
    color: #777777;
    background-color: inherit;
    font-size: 14px;
}

hr {
    height: 0.2em;
    border: 0;
    color: #CCCCCC;
    background-color: #CCCCCC;
}

.content-box p,.content-box  blockquote,.content-box  ul,.content-box  ol,.content-box  dl,.content-box  li,.content-box  table,.content-box  pre {
    margin: 15px 0;
}

 .content-box p{
    margin:1em 0;
}
.content>p{
  padding-left:15px;
}
pre { 
    background-color: #F8F8F8;    
    border: 1px solid #CCCCCC;
    border-radius: 3px;
    overflow: auto;
    padding: 5px;
    margin-left:15px;
}

pre code {
    background-color: #F8F8F8;
    border: none;    
    padding: 0;
}

code {
    font-family: Consolas, Monaco, Andale Mono, monospace;
    background-color:#F8F8F8;
    border: 1px solid #CCCCCC;
    border-radius: 3px;
    padding: 0 0.2em;
    line-height: 1;
}

pre > code {
    border: 0;
    margin: 0;
    padding: 0;
}


.content-box a{ color: #0645ad; text-decoration:none;}
.content-box a:visited{ color: #0b0080; }
.content-box a:hover{ color: #06e; }
.content-box a:active{ color:#faa700; }
.content-box a:focus{ outline: thin dotted; }
.content-box a:hover,.content-box  a:active{ outline: 0; }

::-moz-selection{background:rgba(255,255,0,0.3);color:#000}
::selection{background:rgba(255,255,0,0.3);color:#000}

.content-box a::-moz-selection{background:rgba(255,255,0,0.3);color:#0645ad}
.content-box a::selection{background:rgba(255,255,0,0.3);color:#0645ad}

blockquote{
    color:#666666;
    margin:0;
    padding-left: 3em;
    border-left: 0.5em #EEE solid;
}

ul, ol { margin: 1em 0; padding: 0 0 0 2em; }
li p:last-child { margin:0 }
.content-box ul li {
  list-style:disc;
}
.content-box ol li {
list-style: unset;
}
.content-box dd { margin: 0 0 0 2em; }

img { border: 0; -ms-interpolation-mode: bicubic; vertical-align: middle; max-width:100%;}

table { border-collapse: collapse; border-spacing: 0; }
td { vertical-align: top; }

@media only screen and (min-width: 480px) {
    body{font-size:14px;}
}

@media only screen and (min-width: 768px) {
    body{font-size:16px;}
} 
img {
   max-width: 100%; }


</style>
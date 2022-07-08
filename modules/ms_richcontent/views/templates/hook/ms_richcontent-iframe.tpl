{if $link_txt && $link_txt != ''}
    <div class="product_rich_content embed-container">
        <iframe id="rich_iFrame" src="{$urls.base_url}rich_content/{$link_txt[$id_lang]}/index.html" title="{$link_txt[$id_lang]}" 
        frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"
        ></iframe> 
    </div>
{/if}

{literal}
<script type="text/javascript">
var framefenster = document.getElementsByTagName("iframe");
var auto_resize_timer = window.setInterval("autoresize_frames()", 400);
function autoresize_frames() {
  for (var i = 0; i < framefenster.length; ++i) {
      if(framefenster[i].contentWindow.document.body){
        var framefenster_size = framefenster[i].contentWindow.document.body.offsetHeight;
        if(document.all && !window.opera) {
          framefenster_size = framefenster[i].contentWindow.document.body.scrollHeight;
        }
        framefenster[i].style.height = framefenster_size + 'px';
      }
  }
}
</script>
{/literal}

{* {literal}
<script>
    var $iframe =  document.querySelector(`iframe`);

    $iframe.addEventListener("load", function() {
        var doc = $iframe.contentDocument;
        var style = doc.createElement("style");
        style.textContent = `.container{max-width:100% !important;}`;
        doc.head.append(style);
    });
</script>  
{/literal} *}
{extends file='customer/page.tpl'}

{block name='page_header_container'}
{/block}

{block name='page_content_container'}

    <div class="custom_page embed-container">
        <iframe id="custom_page_iframe" src="{$module_dir}content/index.html" title="{*$page_title*}" 
        frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"
        ></iframe> 
    </div>

{/block}


{block name='page_footer_container'}
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
                framefenster[i].style.height = (framefenster_size+180) + 'px';
              }
          }
        }
        </script>
    {/literal}
{/block}



<script type="text/javascript">
$(document).ready(function(){
    if($('select[name="YBC_MF_MANUFACTURERS[]"] option[value="0"]').attr('selected')=='selected')
        $('select[name="YBC_MF_MANUFACTURERS[]"] option').prop('selected', true);
    $('select[name="YBC_MF_MANUFACTURERS[]"] option').click(function(){
        if($(this).attr('value')==0 && $(this).attr('selected')=='selected')
        {
            $('select[name="YBC_MF_MANUFACTURERS[]"] option').prop('selected', true);
        }
        $('select[name="YBC_MF_MANUFACTURERS[]"] option').each(function(){
            if($(this).attr('selected')!='selected')
                $('select[name="YBC_MF_MANUFACTURERS[]"] option[value="0"]').prop('selected', false);
        });
    });
    if($('select[name="YBC_MF_MANUFACTURER_HOOK"]').val()=='default')
        $('select[name="YBC_MF_MANUFACTURER_HOOK"]').next('.help-block').hide();
    $('select[name="YBC_MF_MANUFACTURER_HOOK"]').change(function(){
        if($('select[name="YBC_MF_MANUFACTURER_HOOK"]').val()=='default')
            $('select[name="YBC_MF_MANUFACTURER_HOOK"]').next('.help-block').hide();
        else
            $('select[name="YBC_MF_MANUFACTURER_HOOK"]').next('.help-block').show();
    });
    
});
</script>
<style>
select[multiple]{
    height: 115px !important;
}
</style>
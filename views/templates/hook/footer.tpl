{*
* 2006-2021 THECON SRL
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* YOU ARE NOT ALLOWED TO REDISTRIBUTE OR RESELL THIS FILE OR ANY OTHER FILE
* USED BY THIS MODULE.
*
* @author    THECON SRL <contact@thecon.ro>
* @copyright 2006-2021 THECON SRL
* @license   Commercial
*}

<script>
    {if $THDISABLEVS_RIGHT_CLICK}
    // right click
    document.oncontextmenu = function (e) {
        e.preventDefault();
        return false;
    };
    {/if}

    document.onkeydown = function (e) {
        {if $THDISABLEVS_F12}
        if (e.keyCode == 123) {
            return false;
        }
        {/if}

        {if $THDISABLEVS_CTRL_SHIFT_I}
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
            return false;
        }
        {/if}

        {if $THDISABLEVS_CTRL_SHIFT_C}
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
            return false;
        }
        {/if}

        {if $THDISABLEVS_CTRL_SHIFT_J}
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
            return false;
        }
        {/if}

        {if $THDISABLEVS_CTRL_U}
        if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
            return false;
        }
        {/if}

        {if $THDISABLEVS_CTRL_S}
        if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
            return false;
        }
        {/if}
    }
</script>

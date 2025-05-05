function AjaxF()
{
    var ajax;
    
    try
    {
        ajax = new XMLHttpRequest();
    } 
    catch(e) 
    {
        try
        {
            ajax = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e) 
        {
            try 
            {
                ajax = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e) 
            {
                alert("Seu browser não da suporte à AJAX!");
                return false;
            }
        }
    }
    return ajax;
}

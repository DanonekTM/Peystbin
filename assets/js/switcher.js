var request;

$("#themeToggle").change(function(event)
{
    event.preventDefault();

    if (request) 
	{
        request.abort();
    }
    var $form = $(this);
	
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "theme.php",
        type: "post",
        data: serializedData
    });

    request.done(function (response, textStatus, jqXHR)
	{
		document.getElementById("pagestyle").setAttribute("href", "/assets/css/" + response);
		document.getElementById("logo").setAttribute("src", "/assets/images/" + response + "/logo.svg");
		document.getElementById("logo-mini").setAttribute("src", "/assets/images/" + response + "/logo-mini.svg");
    });
});
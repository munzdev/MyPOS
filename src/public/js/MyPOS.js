// Login Model
// ----------

//Includes file dependencies
define(["app", "underscore", "jquery"], function(app)
{
    "use strict";

    var MyPOS = {};

    MyPOS.RenderPageTemplate = function(View, Name, Template, Datas)
    {
        // Sets the view's template property
        View.template = _.template(Template);

        var page = $('#' + Name);

        if(page.length > 0) {
            page.remove();
        }

            //append the new page onto the end of the body
        View.$el.append('<div data-role="page" id="' + Name + '">' + View.template(Datas) + '</div>');

            //initialize the new page
        $.mobile.initializePage();

    };

    MyPOS.RenderDialogeTemplate = function(View, Name, Template, Datas)
    {
        // Sets the view's template property
        View.template = _.template(Template);

        var page = $('#' + Name);

        if(page.length > 0) {
            page.html(View.template(Datas));
        } else {
            //append the new page onto the end of the body
            var dialoge = View.$el.append('<div data-role="page" id="' + Name + '" data-dialog="true" data-close-btn="none">' + View.template(Datas) + '</div>');

            //initialize the new page
            $.mobile.initializePage();
        }
    };

    MyPOS.RenderPopupTemplate = function(View, Name, Template, Datas, Extras)
    {
        // Sets the view's template property
        View.template = _.template(Template);

        var page = $('#' + Name);

        if(page.length > 0) {
            page.html(View.template(Datas));
        } else {
            //append the new page onto the end of the body
            var popup = View.$el.append('<div data-role="popup" id="' + Name + '" ' + Extras + '>' + View.template(Datas) + '</div>');

            //initialize the new page
            $.mobile.initializePage();
        }
    };

    MyPOS.ChangePage = function(View, options)
    {
        Backbone.history.navigate(View, true);
    }

    MyPOS.ReloadPage = function()
    {
        Backbone.history.loadUrl();
    }

    MyPOS.UnloadWebsite = function(result)
    {
        $(location).attr('href', app.URL);
    }

    MyPOS.DisplayError = function(errorMessage)
    {
        alert(errorMessage);
    }

    MyPOS.KeepSessionAlive = function()
    {
        $.ajax({url: app.API + 'Utility/KeepSessionAlive/',
            dataType: 'json'
        });
    }

    MyPOS.DateFromMysql = function(mysql_string)
    {
        var t, result = null;

        if( typeof mysql_string === 'string' )
        {
            t = mysql_string.split(/[- :]/);

            //when t[3], t[4] and t[5] are missing they defaults to zero
            result = new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
        }

       return result;
    }

    return (window.MyPOS = MyPOS);

} );

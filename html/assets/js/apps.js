/**
 *
 *
 *
 *
 */
 
var Apps = {

    filters : {
        'status': 'all',
        'category': 'all',
        'search': ''
    },
    
    options: {
        item: '<li><div class="image"><img src="assets/img/pixel.gif" class="lazy"></div><div class="text"><div class="name"></div><div class="comment"></div><div class="icon hide"></div><div class="package hide"></div></div></li>',
        item1: '<li><div class="image"><img src="assets/img/pixel.gif" class="lazy"></div><div class="text"><div class="name"></div><div class="package hide"></div><div class="icon "></div></div></li>',
        page: 500,
        valueNames: [ 'name', 'package' ],
        plugins: [
            [ 'fuzzySearch', {
                searchClass: "fuzzy-search",
                location: 0,
                distance: 100,
                threshold: 0.4,
                multiSearch: true
            }],
            [ 'paging', {
	            pagingClass: "pages",
	            innerWindow: 1,
        	    left: 2,
        	    right: 3
        	}]
        ]
    },
    
    init: function() {


        // Fetch data and init list
        //$.getJSON('../data/packages.json', Apps.load);
        Apps.load(packages);
        
        // Categories
        $("aside a").on("click", function(e){
            $("aside li").removeClass("active");
            $(this).parent("li").addClass("active");
            Apps.filters.category = $(this).attr('href').substr(1);
	        Apps.filter();
        });

        // Status
        $(".pill").on("click", function() {
            var pills = $(this).parent().find(".pill");
            pills.removeClass("active");
            $(this).addClass("active");
            Apps.filters.status = $(this).attr('href').substr(1);
            Apps.filter();
        });
        
        // Menu        
        $('.dropdown-toggle').dropdown();
        $(".view-as").on("click", function() {
            $("#list").removeClass("icons-view list-view grid-view");
            $("#list").addClass($(this).attr("href").substr(1));
        });
        
        // Search
        $("#search").focus();

    },
    
    load: function(json) {
  
        Apps.appList = new List('app-list', Apps.options, json);
        // Icon lazy loading
        $("#list li .icon").each(function(index){
            var src = "../data/icons/" + $(this).text();
	        $(this).closest("li").find("img").data("original",src);
        });
	    $("img.lazy").lazyload({ 
            threshold : 300
        });
        $("#loading").hide();        
    
        // Click list item
        
        ich.addTemplate('info', $("#info").html());
        
        $("body").on("click", "#list > li", function() {
        
            // load panel
            $.getJSON("../data/packages/" + $(this).find(".package").html() + ".json" , function(json) {
                var html = ich.info(json);
                $("#info").html(html);
                $("#info").modal();
            });
        });    
	    
    },
    
    filter: function() {

        Apps.appList.filter(function(item){
	
            if(item.values().category == Apps.filters.category || Apps.filters.category == 'all') {
                if(Apps.filters.status=='all') return true;
                
                if(Apps.filters.status=='installed') {
                    if(installed.indexOf(item.values().package)!=-1) {
                        return true;
                    } else {
                        return false;
                    }
                }
                if(Apps.filters.status=='featured') {
                    if(featured.indexOf(item.values().package)!=-1) {
                        return true;
                    } else {
                        return false;
                    }
                }
                
                return true;
                // search
                
            } else {
                return false;
            }
       
        });
        // $("img.lazy").lazyload({ 
        //     //effect : "fadeIn",
        //     threshold : 300
        // });
    },
    

};



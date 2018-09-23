<div>
    <ul class="uk-breadcrumb">
        <li><a href="javascript:;">@lang('Utils')</a></li>
        <li class="uk-active"><span>@lang('ApiTester')</span></li>
    </ul>
</div>

<div class="uk-margin-top uk-grid" riot-view>

   <div id="page-offcanvas" class="uk-offcanvas">
       <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
         <div class="uk-grid">
            <div class="uk-width-1-5">
               <label class="uk-text-small">@lang('Show Only Favorites')?</label>
               <cp-field type="boolean" bind="show_only_favs"></cp-field>
            </div>
            <div class="uk-width-1-5">
               <label class="uk-text-small">@lang('Allow Deletion')?</label>
               <cp-field type="boolean" bind="allow_deletion"></cp-field>
            </div>
            <div class="uk-width-1-2 uk-form">
               <input class="uk-width-1-1 uk-form-large" type="text" placeholder="@lang('Search')..." bind="ql_search_term">
            </div>
         </div>
         <div>
            <hr/>
            <a if="{ allow_deletion }" href="#" data-delete="-1" class="uk-button uk-button-default uk-margin-bottom" onclick="{ this.remove }">@lang('Delete All Log Entries')</a>
         </div>
         <div id="ql_acc-container">
            <ul uk-accordion id="querylog-acc" class="uk-accordion">
                <!--<li each="{logentry, $index in querylog}" if="{ !(show_only_favs && !logentry.fav) }">-->
                <li each="{logentry, $index in querylog}" 
                  class="{ !(show_only_favs && !logentry.fav || !(logentry.title.match(ql_search_term) || moment(1000 * logentry._created).format('DD.MM.Y HH:mm:ss').match(ql_search_term)) ) ? '' : 'uk-hidden' }">
                    <a if="{ allow_deletion }" href="#" data-delete="{ logentry._id }" class="ql_delete-log-entry" onclick="{ this.remove }"><i data-delete="{ logentry._id }" class="uk-icon-trash"></i></a>
                    <a class="uk-accordion-title" href="#" onclick="
                        window.current_log_index = { $index }; 
                        window.current_log_id = '{ logentry._id }'; 
                        ">
                        <i class="uk-icon-star ql_log-entry-star-mod ql_log-entry-star-{ logentry.fav ? 'active' : 'inactive' }"></i> { logentry.title ? logentry.title : moment(1000 * logentry._created).format('DD.MM.Y HH:mm:ss') }
                    </a>
                    <div class="uk-accordion-content">
                        <p if="{ logentry.title }">{ moment(1000 * logentry._created).format('DD.MM.Y HH:mm:ss') }</p>
                        <p>{ logentry.api_endpoint }</p>
                        <p><b>Param:</b> { logentry.param }</p>
                        <pre>{ logentry.query }</pre>
                        <blockquote>{ logentry.comment }</blockquote>
                        <p>
                           <a href="#" class="uk-button uk-button-default" data-rerunindex="{ $index }" onclick="{ this.rerun }">Re-Run</a>
                           <a href="javascript:window.querylog_editor_modal.show()" class="uk-button uk-button-default" onclick="{ this.update }">Edit Log Meta</a>
                        </p>                     
                    </div>
                </li>
            </ul>
         </div>
       </div>
   </div>

   <div id="querylog-detail-editor" class="uk-modal">
       <div class="uk-modal-dialog">
           <a class="uk-modal-close uk-close"></a>
            <form id="querylog-form" class="uk-form" onsubmit="{ submit_querylog_editor }">
               <div class="uk-form-row">
                  <label class="uk-text-small">@lang('Query Log Title')</label>
                  <input class="uk-width-1-1 uk-form-large" type="text" bind="querylog[{window.current_log_index}].title">
               </div>
               <div class="uk-form-row">
                  <label class="uk-text-small">@lang('Query Log Comment')</label>
                  <input class="uk-width-1-1 uk-form-large" type="text" bind="querylog[{window.current_log_index}].comment">
               </div>
               <div class="uk-form-row">
                  <label class="uk-text-small">@lang('Favorize')?</label>
                  <cp-field type="boolean" bind="querylog[{window.current_log_index}].fav"></cp-field>
               </div>
               <div class="uk-form-row">
                  <button class="uk-button uk-button-large uk-button-primary uk-width-1-1">@lang('Save')</button>
               </div>
            </form>
       </div>
   </div>

   <div class="uk-width-medium-5-10">

      <div class="uk-panel">

         <div class="uk-grid" data-uk-grid-margin>

            <div class="uk-width-medium-1-1">

               <form id="account-form" class="uk-form" onsubmit="{ submit }">

                  <div class="uk-form-row">
                     <label class="uk-text-small">@lang('Current Users API Key')</label>
                     <input class="uk-width-1-1 uk-form-large" type="text" bind="api_key">
                  </div>

                  <div class="uk-form-row">
                     <label class="uk-text-small">@lang('API Endpoint')</label>
                     <cp-field type="select" cls="api_endpoints__select" bind="api_endpoint" opts="{ this.api_endpoints }" required></cp-field>
                  </div>

                  <div class="uk-form-row">
                     <label class="uk-text-small">@lang('Param')</label>
                     <input class="uk-width-1-1 uk-form-large" bind="param" type="text" placeholder="\{collectionname\} / \{form_name\} / \{regionname\} / \{singletonname\}">
                  </div>

                  <div class="uk-form-row">
                     <label class="uk-text-small">@lang('Query')</label>
                     <cp-field type="code" bind="query" opts="{ this.code_field_opts }"></cp-field>
                  </div>

                  <div class="uk-form-row">
                     <label class="uk-text-small">@lang('Resulting URL')</label>
                     <textarea class="uk-width-1-1 uk-form-large" type="text" bind="result_url" readonly style="height: 150px" placeholder="please run a query to get the request url string"></textarea>

                  </div>

                  <div class="uk-form-row">
                     <button class="uk-button uk-button-large uk-button-primary uk-width-1-1">@lang('Run')</button>
                  </div>

               </form>

            </div>
         </div>
      </div>
   </div>

   <div class="uk-width-medium-5-10">
      <div class="uk-form-row">
         <label class="uk-text-small">@lang('Query return / result')</label>
         <cp-field type="code" bind="run_result" opts="{ this.code_field_opts }" id="run_results" readonly></cp-field>
         <input class="uk-width-1-1 uk-form-large" type="text" bind="result_length" readonly placeholder="Number of results...">
         
         <a href="#page-offcanvas" data-uk-offcanvas="{mode:'slide'}" class="uk-button uk-button-default uk-margin-top">querylog</a>
      </div>
   </div>

    <script type="view/script">
    
         var $this = this;

         this.mixin(RiotBindMixin);
         
         // concrete data passed by the controller on page load
         this.account  = {{ json_encode($account) }};
         this.querylog  = {{ json_encode($querylog) }};

         // json code field (cp tag) default settings 
         this.code_field_opts = {
            "syntax": "json",
            "height": "auto"
         };

         // api endpoints select options
         this.api_endpoints = {
           "options": [
              "---",
              "COLLECTIONS:",
              "---",
              "/api/collections/listCollections",
              "/api/collections/collection/{collectionname}",
              "/api/collections/updateCollection/{collectionname}",
              "/api/collections/get/{collectionname}",
              "/api/collections/save/{collectionname}",
              "/api/collections/remove/{collectionname}",
              "---",
              "COCKPIT",
              "---",
              "/api/cockpit/authUser",
              "/api/cockpit/saveUser",
              "/api/cockpit/listUsers",
              "/api/cockpit/assets",
              "/api/cockpit/image",
              "---",
              "FORMS",
              "---",
              "/api/forms/submit/{form_name}",
              "---",
              "REGIONS",
              "---",
              "/api/regions/listRegions",
              "/api/regions/get/{regionname}",
              "/api/regions/data/{regionname}",
              "---",
              "SINGLETONS",
              "---",
              "/api/singletons/listSingletons",
              "/api/singletons/get/{singletonname}",
           ]
        };

        // bindings
        this.api_key = this.account.api_key;
        this.api_endpoint = '/api/collections/get/{collectionname}';
        this.param = 'test';
        this.query = JSON.stringify({"filter" : {"published":true}}, null, 2);
        
        this.result_url = '';
        this.result_length = '-';
        this.run_result = 'No query executed yet';

        window.current_log_index = 0; // set when a query log entry in sidebar is selected
        window.current_log_id = 0; // .. same 
        
        // query log filter binding vars
        this.show_only_favs = false;
        this.allow_deletion = false;
        this.ql_search_term = '';
        
        this.on('mount', function(){
            if($this.querylog.length > 0) // init log entry accordion
               window.acc = UIkit.accordion('#querylog-acc', {showfirst: false});
            
            // init log meta editor modal
            window.querylog_editor_modal = UIkit.modal("#querylog-detail-editor");
        });

        // remove one or all logged queries handler
        remove(e) {
            var delete_entry = e.target.dataset.delete;
            if(delete_entry==='-1') 
               delete_entry = confirm('Are you sure you want to completely remove all log entries?') ? delete_entry : 0; // entry id 0 will never exists; therefor this create an empty request resulting in no deletion but not blocking the rest of this handler
            App.request("/apitester/removequery", {entry:delete_entry}).then(function(ret){
               $this.querylog = ret;
               $this.update();
               window.acc.update();
            });
        }
        
        // re-run logged query button handler
        rerun(e) {
           var entry_id = e.target.dataset.rerunindex; 
            
           this.api_key = this.querylog[entry_id].api_key;
           this.api_endpoint = this.querylog[entry_id].api_endpoint;
           this.param = this.querylog[entry_id].param;
           
           this.query = JSON.stringify(JSON.parse(this.querylog[entry_id].query), null, 2);
           $('codemirror')[0].editor.getDoc().setValue(this.query);
           
           UIkit.offcanvas.hide();
           App.ui.notify("Query restored! Hit the RUN button!", "success");
        }
        
        // edit log entry meta handler
        submit_querylog_editor(e) {
            if(e) e.preventDefault();
            
            App.request( "/apitester/savequery", { "query_data": this.querylog[window.current_log_index] } ).then(function(ret){
               $this.update();
               window.acc.update();
               App.ui.notify("Log entry updated!", "success");
            });
            
            return false;
         }
        
         // api test requst handler
         submit(e) {

            if(e) e.preventDefault();

            if(this.query.trim().length===0) // prevent errors due emmpty filters (actually empty filter are okay, but the next row crashes within JSON.parse if the filters are not set)
               this.query = '{}';
            
            this.result_url = this.api_endpoint.replace(/\{.*\}/, this.param) + "?token="+this.api_key + "&"+$.param(JSON.parse(this.query));//+serialize(this.query);

            var data = {
              'api_key' : this.api_key,
              'api_endpoint' : this.api_endpoint,
              'result_url' : this.result_url,
              'param' : this.param,
              'query' : this.query,
              'fav' : false,
              'title' : '',
              'comment' : ''
            };

            // just run the users query agains tha selected api endpoint and display the result
            App.request(this.result_url, this.query).then(function(data) {
               $this.result_url = location.protocol + '//' + location.host + $this.result_url ;

               $('codemirror')[1].editor.getDoc().setValue(JSON.stringify(data, null, 2));

               $this.result_length = data.total || data.itemsCount || data.length;
           },
           function(e){
               console.info(e);
               App.ui.notify(e.error, "danger");
           });
           
           // log the query
           App.request( "/apitester/savequery", {"query_data": data} ).then(function(ret){
               $this.querylog.unshift(ret);
               $this.update();
               window.acc.update();
           });
           
           return false;
           
        }
        
        // disable option values within the api point select that actually are no api endpoints
        $(function(){
            $('.api_endpoints__select').find('option').each(function(i,v){
               if(i === 0)
                  $(v).text('please choose...'.toUpperCase());
               if($(v).val().substr(0,1) !== '/')
                  $(v).prop('disabled', true);
            });
        });

    </script>

</div>

<template>
   <!-- Modal -->
<div class="modal fade" id="modal-edit-granted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">

          <form-edit
            :provinces="provinces"
       	    :types="types"
       	    :modal-granted="modalGranted"
       	    >
       	  </form-edit>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
</template>

<script>
	import CompFrom from './form-edit-granted.vue'
    export default {
    	props: {
    		provinces: {
    			type: Array
    		},
    		types: {
    			type: Array
    		},
    		modalGranted: {
    			type: Array
    		}
    	},
        mounted(){

        },
        components: {
        	'form-edit': CompFrom
        }
    }
</script>

<template>
	<div class="col-md-4">
		<div class="panel panel-default">
		  <div class="panel-heading">Create assistance type</div>
		  <div class="panel-body">
			  	<form @submit.prevent="submitForm">
			  	 	<label>
			  	 		Assistance Type:
			  	 		<input v-model="assistanceName" type="text" class="form-control">
			  	 	</label>
			  	 	<button class="btn btn-info">submit</button>
			  	 </form>
		  </div>
		</div>
	</div>
</template>

<script>
	import toastr from 'toastr'
    export default {
    	data(){
    		return {
    			assistanceName: ''
    		}
    	},
        mounted() {
            // console.log('Component mounted.');
        },
        methods: {
        	submitForm(){
        		let self = this;
        		self.$http.post('/type', {
        			name: self.assistanceName
        		}).then((resp) => {
        			if (resp.status === 200) {
        				let json = resp.body;
        				toastr.success('Successfull');
        				self.assistanceName = '';
        				self.$emit('newtype', json);
        			}
        		}, (resp) => {
        			console.log(resp);
        		})
        	}
        }
    }
</script>

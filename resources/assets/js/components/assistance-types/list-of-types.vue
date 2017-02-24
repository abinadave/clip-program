<template>
    <div class="col-md-6">
    	<div class="panel panel-info">
    		<div class="panel-heading">List {{ types.length }}</div>
    		<div class="panel-body">
    			<table class="table table-hover table-condensed">
    				<thead>
    					<tr>
    						<th>Type of Assistance</th>
    						<th>Granted</th>
    						<th>Pending</th>
    						<th width="1"></th>
    					</tr>
    				</thead>
    				<tbody>
    					<tr v-for="(type, index) in types">
    						<td>{{ type.name }}</td>
    						<td>0</td>
    						<td>0</td>
    						<td><a style="cursor:pointer" @click="deleteType(type, index)"><span class="glyphicon glyphicon-remove"></span></a></td>
    					</tr>
    				</tbody>
    			</table>
    		</div>
    	</div>
        

    </div>
</template>

<script>
	import toastr from 'toastr'
    export default {
        mounted(){

        },
        props: {
        	types: {
        		type: Array
        	}
        },
        methods: {
        	deleteType(type, index){
        		let self = this;
        		let confirmed = confirm('Are you sure ?');
        		if (confirmed) {
        			let resource = self.$resource('type/{id}');
	        		resource.delete({id: type.id}).then(resp => {
					  	if (resp.status === 200) {
					  		let json = resp.body;
					  		if (json.deleted === true) {
					  			self.types.splice(index, 1);
					  			toastr.warning('['+type.name.toUpperCase() + '] Assistance was deleted');
					  		}
					  	}
					}, resp => {
					  	// error callback
					});
        		}
        		
        		
        	}
        }
    }
</script>

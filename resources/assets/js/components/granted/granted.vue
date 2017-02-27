<template>
    <div class="container">
        <div class="panel panel-default">
		  <div class="panel-heading">Panel Heading</div>
		  <div class="panel-body">
		  <input v-model="search" type="text" class="form-control" placeholder="Search" style="width: 220px">
		  <select class="form-control pull-right" style="width: 30%; margin-top: -36px">
		  	 <option :value="0">All</option>
		  	 <option :value="type.id" v-for="type in types">
		  	 	{{ type.name }}
		  	 </option>
		  </select><hr>
		  	 <table class="table hover table-striped">
		  	 	<thead>
		  	 		<tr>
		  	 			<th>Province</th>
		  	 			<th>FR Name</th>
		  	 			<th>Type of Assistance</th>
		  	 			<th>Address</th>
		  	 			<th>Date Submitted</th>
		  	 			<th>Amount</th>
		  	 			<th>Action Taken</th>
		  	 		</tr>
		  	 	</thead>
		  	 	<tbody>
		  	 		<tr v-for="granted in filteredList">
		  	 			<td>{{ getProvince(granted.province) }}</td>
		  	 			<td>{{ granted.fr_name }}</td>
		  	 			<td>{{ getType(granted.type_of_assistance) }}</td>
		  	 			<td>{{ granted.address }}</td>
		  	 			<td>{{ formatDate(granted.date_submitted) }}</td>
		  	 			<td><b>{{ formatAmmount(granted.amount) }}</b></td>
		  	 			<td>{{ getActionTaken(granted.action_taken) }}</td>
		  	 		</tr>
		  	 	</tbody>
		  	 </table>
		  </div>
		</div>
    </div>
</template>

<script>
	import moment from 'moment'
	import accounting from 'accounting'
    export default {
        mounted(){
        	this.fetch();
        },
        computed: {
        	filteredList(){
        		let self = this;
        		let search = self.search.toLowerCase();
        		let rsProvince = [], province = {};
        		let rsTypes = [], type = {};
        		return self.assists.filter(function(index) {
        			rsProvince = _.filter(self.provinces, {id: index.province});
        			rsTypes = _.filter(self.types, {id: index.type_of_assistance});
        			province = (rsProvince.length > 0) ? rsProvince[0].name : '';
        			type = (rsTypes.length > 0) ? rsTypes[0].name : '';
        			return index.fr_name.toLowerCase().indexOf(search) !== -1 ||
        				   index.address.toLowerCase().indexOf(search) !== -1 ||
        				   index.amount.toString().toLowerCase().indexOf(search) !== -1 ||
        				   province.toLowerCase().indexOf(search) !== -1 ||
        				   type.toLowerCase().indexOf(search) !== -1
        		});
        	}
        },
        data(){
        	return {
        		assists: [],
        		types: [],
        		provinces: [],
        		search: ''
        	}
        },
        methods: {
        	getActionTaken(action){
        		let self = this;
        		if (action === '') {
        			return 'none';
        		}
        	},
        	formatAmmount(amount){
        		let self = this;
        		return accounting.formatMoney(amount, '', 2);
        	},
        	formatDate(date){
        		let self = this;
        		return moment(date).format('MMMM DD, YYYY, ddd');
        	},
        	getProvince(province_id){
        		let self = this;
        		let rs = _.filter(self.provinces, {id: province_id});
        		return (rs.length > 0) ? rs[0].name : 'Not found';
        	},
        	getType(type){
        		let self = this;
        		let rs = _.filter(self.types, {id: type});
        		return (rs.length > 0) ? rs[0].name : 'Not found';
        	},
        	fetch(){
        		let self = this;
        		self.$http.get('/granted').then((resp) => {
        		    if (resp.status === 200) {
        		        let json = resp.body;
        		        self.types = json.types;
        		        self.provinces = json.provinces;
        		        self.assists = json.assists;
        		    }
        		}, (resp) => {
        		    if (resp.status === 422) {
        		      console.log(resp);
        		    }
        		});
        	}
        }
    }
</script>

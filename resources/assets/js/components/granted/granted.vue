<template>
   <div>
    <div class="container">

        <div class="panel panel-default">
		  <div class="panel-heading">List of Clients 
             <a @click="editTr" :class="{ 'pencil-edit pull-right': true }"><i class="fa fa-2x fa-pencil-square" aria-hidden="true" style="display: inline"></i></a>
          </div>
		  <div class="panel-body">
          
		  <input v-model="search" type="text" class="form-control" placeholder="Search" style="width: 220px; border-radius: 25px;">
		  
          <select v-model="selectedType" class="form-control pull-right" style="width: 30%; margin-top: -36px;display: inline">
		  	 <option :value="0">All</option>
		  	 <option :value="type.id" v-for="type in types">
		  	 	{{ type.name }}
		  	 </option>
		  </select><hr>
             <div style="overflow: auto; height: 500px">
    		  	 <table class="table table-hover table-striped table-bordered">
    		  	 	<thead>
    		  	 		<tr class="text-warning">
    		  	 			<th>PROVINce</th>
    		  	 			<th>FR NAME</th>
    		  	 			<th>TYPE OF ASSISTANCE</th>
    		  	 			<th>ADDRESS</th>
    		  	 			<th>DATE SUBMITTED</th>
    		  	 			<th>ACTION TAKEN</th>
    		  	 			<th style="text-align: right">AMOUNT</th>
    		  	 		</tr>
    		  	 	</thead>
    		  	 	<tbody>
    		  	 		<tr :id="granted.id" @click="selectFilteredList(granted)" v-for="granted in filteredList">
    		  	 			<td>{{ getProvince(granted.province) }}</td>
    		  	 			<td>{{ granted.fr_name }}</td>
    		  	 			<td>{{ getType(granted.type_of_assistance) }}</td>
    		  	 			<td>{{ granted.address }}</td>
    		  	 			<td>{{ formatDate(granted.date_submitted) }}</td>
    		  	 			<td>{{ getActionTaken(granted.action_taken) }}</td>
    		  	 			<td style="text-align: right"><b>{{ formatAmmount(granted.amount) }}</b></td>
    		  	 		</tr>
    		  	 	</tbody>
                   
    		  	 </table>
             </div>
             <div>
                <h5 class="pull-left" style="font-size: 20px"><b>TOTAL:</b></h5>
                 <h5 class="pull-right text-primary" style="font-size: 20px">P<b>{{ formatAmmount(totalAmount) }}&nbsp;&nbsp;&nbsp;&nbsp;</b></h5>
             </div>
		  </div>
		</div>
    </div>
    <modal-edit-granted
        :provinces="provinces"
        :types="types"
        :modal-granted="modalGranted"
    ></modal-edit-granted>
</div>
</template>
<style type="text/css">
    tbody tr {
        cursor: pointer;
    }
    .pencil-edit {
        cursor: pointer;
        margin-left: 10px;
    }
</style>
<script>

	import moment from 'moment'
	import accounting from 'accounting'
	import CompModalEdit from './modal-update-granted.vue'
    export default {
        mounted(){
        	this.fetch();
        },
        data(){
        	return {
        		assists: [],
        		types: [],
        		provinces: [],
        		search: '',
        		selectedType: 0,
                totalAmount: 0,
                currentTrClicked: 0,
                modalGranted: []
        	}
        },
        components: {
            'modal-edit-granted': CompModalEdit
        },
        methods: {
            editTr(tr){
                let self = this;
                let rs = _.filter(self.assists, {id: self.currentTrClicked});
                self.modalGranted = rs;
                $('#modal-edit-granted').modal('show');
            },
            selectFilteredList(granted){
                /* when tr is clicked */
                let self = this;
                self.currentTrClicked = granted.id;
                let $el = $(self.$el);
                $el.find('tbody').find('tr').removeClass('text-primary');
                $el.find('tbody tr#'+granted.id).addClass('text-primary');
            },
        	getActionTaken(action){
        		let self = this;
        		if (action === '') {
        			return '-';
        		}
        	},
        	formatAmmount(amount){
        		let self = this;
        		return accounting.formatMoney(amount, '', 2);
        	},
        	formatDate(date){
        		let self = this;
        		let formated = moment(date).format('MMMM DD, YYYY, ddd');
                if (formated === 'Invalid date') {
                    return '-';
                }else {
                    return formated;
                }
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
        	},
            caculateTotal(amounts){
                let self = this;
                let amount = 0;
                let total = 0;
                for (var i = 0, len = amounts.length; i < len; i++) {
                    amount = Number(amounts[i]);
                    total += amount;
                }
                self.totalAmount = total;
            }
        },
        computed: {
        	filteredList(){
        		let self = this;
        		let search = self.search.toLowerCase();
        		let rsProvince = [], province = {};
        		let rsTypes = [], type = {};
        		let arr = self.assists.filter(function(index) {
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
                self.caculateTotal(_.map(arr, 'amount'));
                return arr;
        	}
        },

        watch: {
        	'selectedType': function(newVal){
        		let self = this;
        		var resource = self.$resource('assist/display/by/type{/id}');
				  // GET someItem/1
				  resource.get({id: newVal}).then(resp => {
				      if (resp.status === 200) {
				      	 let json =resp.body;
				      	 self.assists = json.assists;
				      }
				  });

        	}
        }
       
    }
</script>

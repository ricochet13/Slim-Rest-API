<? require_once 'header.php' ?>
 
<script type="text/javascript">
var app=angular.module('app',[]);

app.controller('usersCtrl',
function usersCtrl($scope,$http,$window){
$http.defaults.headers.post['Content-Type']='application/x-www-form-urlencoded;charset=utf-8';

$scope.tot=0;

function load(x,y){
$http.get("<?=$url.'/gusers/'?>"+x+"/"+y).success(function(data, status, headers, config){
$scope.users=data;
if($scope.tot==0){$scope.tot=$scope.users[0].lp;$scope.pn=1;$scope.tnav=1;}
});}

load(1,10);

$scope.delete = function(id,idx){
if (!confirm('Are you sure?'))return;
$http({method : "DELETE",
url:'<?=$url.'/deluser/'?>'+id
}).success(function(data){$scope.un="";$scope.fn="";$scope.pwd="";
$scope.users.splice(idx,1);
});
}

$scope.add = function(){
if($scope.aun!=undefined && $scope.afn!=undefined && $scope.apwd!=undefined){
$http.post('<?=$url.'/adduser'?>',{u:$scope.aun,f:$scope.afn,p:$scope.apwd})
.success(function(data, status, headers, config){
$scope.aun="";$scope.afn="";$scope.apwd="";
$scope.users.push(data);
}).
error(function(data, status, headers, config){
$scope.aun="";$scope.afn="";$scope.apwd="";alert('Error!');
});
}}

$scope.edit = function(u){$scope.un=u.uname;$scope.fn=u.fullname;$scope.pwd="";$scope.uid=u.id;}

$scope.update = function(){
if($scope.efn!=undefined){
$http.post('<?=$url.'/edituser'?>',{f:$scope.efn,p:$scope.epwd,uid:$scope.uid})
.success(function(data, status, headers, config){$scope.eun="";$scope.efn="";$scope.epwd="";
load($scope.tnav,$scope.drpp);
}).
error(function(data, status, headers, config) {
$scope.eun="";$scope.efn="";$scope.epwd="";alert('Error!');
});
}}

$scope.first=function(){if($scope.tnav!=1){load(1,$scope.drpp);$scope.pn=1;$scope.tnav=1;}}

$scope.next=function(){
if($scope.pn!=$scope.tot){$scope.pn=Number($scope.pn)+1;load($scope.pn,$scope.drpp);
$scope.tnav=$scope.pn;}
}

$scope.prev=function(){
if($scope.pn>1){$scope.pn=Number($scope.pn)-1;load($scope.pn,$scope.drpp);
$scope.tnav=$scope.pn;}
}

$scope.last=function(){if($scope.tnav!=$scope.tot){load($scope.tot,$scope.drpp);
$scope.pn=$scope.tot;$scope.tnav=$scope.tot;}}

$scope.chgRPP=function(){$scope.tot=0;load(1,$scope.drpp);}

});

</script>

<br/><br/><br/><br/><br/>
<div ng-app="app">
<div ng-controller="usersCtrl">

<div class="container">
    <div class="row">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
			<tr>
				<th colspan="3">
					<form class="form-horizontal">
						<input id="textinput" name="textinput" placeholder="Search" class="input-xlarge" type="text"
							   ng-model="searchText"/>
					
					<button class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#modalAdd"><i class="glyphicon glyphicon-plus"></i> Add</button>
					
					<button ng-disabled="drpp==0" ng-click="last()" class="btn btn-default pull-right btn-sm"><i class="glyphicon glyphicon-fast-forward"></i></button>
					<button ng-disabled="drpp==0" ng-click="next()" class="btn btn-default pull-right btn-sm"><i class="glyphicon glyphicon-step-forward"></i></button>
					<input type="text" class="pull-right" style="width:40px;text-align:center" ng-model="tnav" readonly>
					<button ng-disabled="drpp==0" ng-click="prev()" class="btn btn-default pull-right btn-sm"><i class="glyphicon glyphicon-step-backward"></i></button>
					<button ng-disabled="drpp==0" ng-click="first()" class="btn btn-default pull-right btn-sm"><i class="glyphicon glyphicon-fast-backward"></i></button>
					<select ng-init="drpp=10" ng-model="drpp" ng-change="chgRPP()" class="pull-right">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="0">All</option>
					</select>
					</form>
				</th>
				
			</tr>
			</thead>
			<tbody>
			<tr ng-repeat="u in users | filter:searchText">
			<td data-title="'UserName'">					
					<a href="#">
						<span>{{u.uname}}</span>
					</a>
				</td>
				<td data-title="'FullName'">
						<span>{{u.fullname}}</span>					
				</td>
				<td>
					<div class="btn-group pull-right">
						<button class="btn btn-warning btn-xs" ng-click="edit(u)" title="edit" data-toggle="modal" data-target="#modalEdit">
							<i class="glyphicon glyphicon-pencil"></i>
							</button>
						<button class="btn btn-danger btn-xs" ng-click="delete(u.id,$index)" title="delete">
						<i class="glyphicon glyphicon-remove"></i></button>
					</div>
				</td>
			</tr>
			</tbody>
		</table>		
	</div>
	
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAddLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalAddLabel">Add User</h4>
      </div>
      <div class="modal-body">
        <input type="text" placeholder="User Name" ng-model="aun" value="">
		<input type="text" placeholder="Full Name" ng-model="afn" value="">
		<input type="password" placeholder="Password" ng-model="apwd" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="add()" data-dismiss="modal">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalEditLabel">Edit User</h4>
      </div>
      <div class="modal-body">
        <input type="text" placeholder="User Name" ng-model="eun" value="{{un}}" readonly>
		<input type="text" placeholder="Full Name" ng-model="efn" value="{{fn}}">
		<input type="password" placeholder="Password" ng-model="epwd" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="update()" data-dismiss="modal">Update</button>
      </div>
    </div>
  </div>
</div>	
	
</div></div></div>

<? require_once 'footer.php' ?>

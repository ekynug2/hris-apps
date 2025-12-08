<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ include file="include.jsp" %>
<jsp:useBean id= "devicAction" scope= "request" class= "com.zk.pushsdk.util.PushUtil"> </jsp:useBean>
<html>
<head>
<title>PUSH WEB DEMO</title>
<%@ include file="includejs.jsp" %>
<%@ include file="deviceDialogs.jsp"%>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<link rel="stylesheet" type="text/css" href="./css/base.css">
<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="./css/easyui.css">
</head>

<body>
<%@ include file="top.jsp" %>
    <div class="text">Command Example</div><br>
    <table>
        <tr>
    		<td>
    			<div align="left" class="form_text" style="float: left">Device</div>
    		</td>
    		<td>
			    <select name="sn" id="group_option" style="width:200px">
			     <%=devicAction.getDeviceOption() %>
			    </select>
    		</td>
    	</tr>
    	<tr>
    		<td colspan="2">1.DATA CRUD
    		<br/>
    			<select id="personOpt" onchange="personOpt(this[selectedIndex].value);"><option>Person Operation</option>
    				<option value="0">Add/Update Personnel</option>
    				<option value="1">Delete Personnel</option>
    				<option value="2">Query All Personnel</option>
    				<option value="3">Count Personnel</option>
    			
    			</select>
    			<!-- <select id="timezone" onchange="timezoneOpt(this[selectedIndex].value);"><option>Time Segment Operation</option>
    				<option value="0">Add/Update Time Zone</option>
    				<option value="1">Delete Time Zone</option>
    				<option value="2">Query Time Zone</option>
    				<option value="3">Count Time Zone</option>
    			
    			</select> -->
    			<!-- <select id="accesslevel" onchange="levelOpt(this[selectedIndex].value);"><option>Access Level Operation</option>
    				<option value="0">Add/Update Access Level</option>
    				<option value="1">Delete Access Level</option>
    				<option value="2">Query Access Level</option>
    				<option value="3">Count Access Level</option>
    			
    			</select> -->

    			<select id="transaction" onchange="transactionOpt(this[selectedIndex].value);"><option>Transaction Operation</option>
    				<option value="0">Add/Update Transaction</option>
    				<option value="1">Delete Transaction</option>
    				<option value="2">Query Transaction</option>
    				<option value="3">Count Transaction</option>
    			
    			</select>
    			<select id="templateoperation" onchange="templateoperationOpt(this[selectedIndex].value);"><option>Template Operation</option>
    				<option value="0">Add/Update Template</option>
    				<option value="1">Delete Template</option>
    				<option value="2">Query Template</option>
    			
    			</select>
    		 </td>
    	</tr>
    	<!-- <tr>
    		<td colspan="2">2.Configuration<br/>
    		<input id="webserverIp" type="button" value="Set WebServerIP And Port" onclick="setServerPara();"> 
    		<input id="driverTime" type="button" value="Set DoorDriverTime" onclick="setDoorDriverTime();"> 
    		</td>
    	</tr> -->
    	<!-- <tr>
    		<td colspan="2">3.Device Controll<br/>
    		<input type="button" value="Remote Control Door 1" onclick="openFirstDoor();"> 
    		<input type="button" value="Cancle All Alarm" onclick="cancleAlarm();"/>
    		<input type="button" value="Reboot" onclick="reboot();"> 
    		</td>
    	</tr> -->
    	<tr>
    		<td></td><td align="left"><font color="red" size="2">The Command will be saved in a text file，and the character “\t” must be replaced by key “Tab”</font></td>
    	</tr>
    	<tr>
    		<td>
    			<div align="left" class="form_text">Command</div>
    		</td>
    		<td>
    			<textarea rows="" cols="" id="cmd_id" class="form_textfield" style="height: 60px;width: 950px"></textarea>
    		</td>
    	</tr>
    </table>
    <input type="submit" value="Send Command" class="button" style="height:28px;width: 140px " onclick="javascript:submitCmd();">
    
    <!-- <br/><br/>
    <div class="text">Returned Information of the Lastest Executed Command</div><br>
    <textarea rows="" cols="" id="last_cmd_data" name="last_cmd_data" class="form_textfield" style="height: 80px;width: 1000px"></textarea><br/> -->
  
    <div class="text">Command And Returned Value </div><br>
    <table  border="1px"  cellpadding="0" cellspacing="0"  id="cmd_table" width="1350px" class="list_table">
    	<tr  align="left" style="border-bottom:  thick dotted #ff0000;" bgcolor="grey" height="30px">
    		<th width="50px" align="center">Command ID</th>
    		<th width="360px">Command Content</th>
    		<th width="100px">Returned Value</th>
    		<th width="380px">Returned Info</th>
    	</tr>
    	
    	<c:forEach var="cmd" items="${cmdList}">
   <tr>
    <td>C:${cmd.devCmdId}</td>
    <td>${cmd.cmdContent}</td>
    <td>${cmd.cmdReturn}</td>
    <td>${cmd.cmdReturnInfo}</td>
  </tr>
  </c:forEach>
  
    </table>
    <br/><br/>
   

</body>
<script type="text/javascript">

	 /* $(document).ready(function(){
		queryCmd();
		queryDevice();
		setInterval(queryCmd,1000);
	}); */
	 
	 
  	function submitCmd() {
  		var sn = $("#group_option").val();
  		var cmd = $("#cmd_id").val();
  		cmd = ltrim(cmd);
  		if(sn== null || cmd=='')
		{
		
  			$.messager.alert('Alert','You must select the device and input the commands first.','error');
		}else{
        $.ajax({
            url:"/deviceAction!queryUpdateCommand.action?sn="+sn + "&cmd=" + encodeURIComponent(cmd) ,
            type: 'post',
            dataType: 'json',
            async : true,
            success: function(data){
            	var jsonObj = data.data;
            	alert(jsonObj);
            }
        })
    }
	}
	
  	function ltrim(str){ //delete space on the left
　　     return str.replace(/(^\s*)/g,"");  
　　 }
  	
  	/*
  		remote open door
  	*/
  	function openFirstDoor(){
  		$("#cmd_id").val("CONTROL DEVICE 01010105");//open door for five seconds
  	}
  	
  	/**
  	*cancle alarm operation
  	*/
  	function cancleAlarm(){
  		$("#cmd_id").val("CONTROL DEVICE 02000000");
  	}
  	
  	
  	
  	/**
  	*	set device's sebserverIP and its port
  	*/
  	function setServerPara(){
  		if($("#webserverIp").val()=="Set WebServerIP And Port"){
  			$("#cmd_id").val("SET OPTIONS WebServerIP=192.168.216.24,WebServerPort=8080");
  			$("#webserverIp").val("Get WebServerIP And Port");
  		}else{
  			$("#cmd_id").val("GET OPTIONS WebServerIP,WebServerPort");
  			$("#webserverIp").val("Set WebServerIP And Port");
  		}
  	}
  	
  	/**
  	*	set door driver time
  	*/
  	function setDoorDriverTime(){
  		if($("#driverTime").val()=="Set DoorDriverTime"){
  			$("#cmd_id").val("SET OPTIONS Door1Drivertime=5");
  			$("#driverTime").val("Get DoorDriverTime");
  		}else{
  			$("#cmd_id").val("GET OPTIONS Door1Drivertime");
  			$("#driverTime").val("Set DoorDriverTime");
  		}
  	}
  	
  	
  	/*
  		device reboot
  	*/
  	function reboot(){
  		$("#cmd_id").val("CONTROL DEVICE 03000000");
  	}

  	
  	/**
  	*person CRUD Option
  	*/
    function personOpt(value){
  		if(value=="0"){
  			$("#cmd_id").val("DATA UPDATE USERINFO PIN=70059\tName=abhi\tPri=0\tPasswd=\tCard=\tGrp=1\tTZ=0000000100000000\tCategory=0");
  		}else if(value=="1"){
  			$("#cmd_id").val("DATA DELETE USERINFO PIN=70059");
  		}else if(value=="2"){
  			$("#cmd_id").val("DATA QUERY USERINFO *");
  		}else if(value=="3"){
  			$("#cmd_id").val("DATA COUNT USERINFO");
  		}else{
  			$("#cmd_id").val("");
  		}
  	}
  	
    /**
  	*time  segment CRUD Option
  	*/
    function timezoneOpt(value){
  		if(value=="0"){
  			$("#cmd_id").val("DATA UPDATE timezone timezoneid=3");
  		}else if(value=="1"){
  			$("#cmd_id").val("DATA DELETE timezone timezoneid=3");
  		}else if(value=="2"){
  			$("#cmd_id").val("DATA QUERY tablename=timezone,fielddesc=*,filter =*");
  		}else if(value=="3"){
  			$("#cmd_id").val("DATA COUNT timezone");
  		}else{
  			$("#cmd_id").val("");
  		}
  	}
  	
    /**
  	*access level CRUD Option
  	*/
    function levelOpt(value){
  		if(value=="0"){
  			$("#cmd_id").val("DATA UPDATE userauthorize pin=1000	authorizetimezoneid=1	authorizedoorid=1");
  		}else if(value=="1"){
  			$("#cmd_id").val("DATA DELETE userauthorize pin=1000	authorizedoorid=1");
  		}else if(value=="2"){
  			$("#cmd_id").val("DATA QUERY tablename=userauthorize,fielddesc=*,filter =*");
  		}else if(value=="3"){
  			$("#cmd_id").val("DATA COUNT userauthorize");
  		}else{
  			$("#cmd_id").val("");
  		}
  	}
    
    
    /**
  	*transaction query Option
  	*/
    function transactionOpt(value){
  		if(value=="0"){
  			$("#cmd_id").val("");alert("Note : event record can not be added or updated manually !");
  		}else if(value=="1"){
  			$("#cmd_id").val("DATA DELETE ATTLOG *");
  		}else if(value=="2"){
  			$("#cmd_id").val("DATA QUERY ATTLOG StartTime=2019-08-19 12:12:12\tEndTime=2020-06-19 12:12:12");
  		}else if(value=="3"){
  			$("#cmd_id").val("VERIFY SUM ATTLOG StartTime=2019-08-19 12:12:12\tEndTime=2020-06-19 12:12:12");
  		}else{
  			$("#cmd_id").val("");
  		}
  	}
    /**
  	*Templatre query Option
  	*/
    function templateoperationOpt(value){
  		if(value=="0"){
  			$("#cmd_id").val("DATA UPDATE BIODATA Pin=70059\tNo=5\tIndex=0\tValid=1\tDuress=0\tType=1\tMajorVer=12\tMinorVer=0\tFormat=0\tTmp=T81TUjIzAAAGjo0FBQUHCc7QAAAej2kAAAA2B==");
  		}else if(value=="1"){
  			$("#cmd_id").val("DATA DELETE BIODATA Pin=2222\tType=1\tNo=5");
  		}else if(value=="2"){
  			$("#cmd_id").val("DATA QUERY BIODATA Type=1\tPin=70059");
  		}else{
  			$("#cmd_id").val("");
  		}
  	}
  	
  	
</script>
</html>

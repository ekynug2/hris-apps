package com.zk.action;

import java.io.IOException;
import java.io.PrintWriter;
import java.text.ParseException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.log4j.Logger;
import org.apache.struts2.interceptor.ServletRequestAware;
import org.apache.struts2.interceptor.ServletResponseAware;
import org.codehaus.jettison.json.JSONException;
import org.codehaus.jettison.json.JSONObject;

import com.zk.manager.ManagerFactory;
import com.zk.pushsdk.po.DeviceAttrs;
import com.zk.pushsdk.po.DeviceInfo;
import com.zk.pushsdk.util.Constants;
import com.zk.pushsdk.util.Constants.DEV_FUNS;
import com.zk.pushsdk.util.PushUtil;
import com.zk.util.PagenitionUtil;

/**
 * Processing request from Device Interface.
 * 
 * @author seiya
 *
 */
public class DeviceAction implements ServletRequestAware,ServletResponseAware{
	private static Logger logger = Logger.getLogger(DeviceAction.class);
	private HttpServletRequest request;
	private HttpServletResponse response;
	private static int curPage = 1;
	private String deviceList = "deviceList";
	
	/**
	 * Update device status information for interface display.
	 * If current time and the last refresh time differ more than 10 minutes,update device status to Offline.
	 * @param deviceInfo
	 */
	private void setDeviceState(DeviceInfo deviceInfo) {
		Date curDate = new Date();
		try {
			Date actDate = PushUtil.dateFormat.parse(deviceInfo.getLastActivity());
			if (curDate.getTime() - actDate.getTime() > 60 * 10 * 1000) {
				deviceInfo.setState("Offline");
			}
		} catch (ParseException e) {
			e.printStackTrace();
		}
	}
	
	/**
	 * Analysis device what is features supported(FP,Face,UserPic) and set function which is supported by character .
	 * 
	 * @param deviceInfo
	 */
	private void setDeviceFuns(DeviceInfo deviceInfo) {
		String devFuns = deviceInfo.getDevFuns();
		if (null != devFuns && !devFuns.isEmpty()) {
			StringBuilder sb = new StringBuilder();
			if (PushUtil.isDevFun(devFuns, DEV_FUNS.FP)) {
				sb.append("FP,");
			}
			if (PushUtil.isDevFun(devFuns, DEV_FUNS.FACE)) {
				sb.append("FACE,");
			} 
			if (PushUtil.isDevFun(devFuns, DEV_FUNS.USERPIC)) {
				sb.append("USERPIC,");
			}
			if (PushUtil.isDevFun(devFuns, DEV_FUNS.BIOPHOTO)) {
				sb.append("BIOPHOTO,");
			}
			if (PushUtil.isDevFun(devFuns, DEV_FUNS.BIODATA)) {
				sb.append("BIODATA,");
			}
			if (sb.length() > 0) {
				sb.deleteCharAt(sb.length() - 1);
			}
			deviceInfo.setDevFuns(sb.toString());
		}
	}
	
	/**
	 * Get Device List
	 * @return 
	 */
	public String deviceList() {
		int recCount = 0;
		int pageCount = 0;
		/**Get interface parameters*/
		String deviceSn = request.getParameter("deviceSn");
		String act = request.getParameter("act");
		String jumpPage = request.getParameter("jump");
		/**Get the total number of records based on conditions*/
		recCount = ManagerFactory.getDeviceManager().getDeviceInfoCount(deviceSn);
		/**Calculate the total number of pages and the current page number*/
		pageCount = PagenitionUtil.getPageCount(recCount);
		curPage = PagenitionUtil.getCurPage(jumpPage, act, pageCount, curPage);
		/**Calculation start recording*/
		int startRec = (curPage - 1) * PagenitionUtil.getPageSize();
		/**Search record lists based on page conditions and page information*/
		List<DeviceInfo> list = ManagerFactory.getDeviceManager().getDeviceInfoListForPage(deviceSn, startRec, PagenitionUtil.getPageSize());
//		System.out.println("list==="+list);
		for (DeviceInfo deviceInfo : list) {
		}
		
		if (null != list) {
			for (DeviceInfo deviceInfo : list) {
				/**If current time and the last connection time differ more than 10 minutes,update device status to Offline.*/
				setDeviceState(deviceInfo);	
				/**Set device functiona*/
				setDeviceFuns(deviceInfo); 
			}
		} else {
			list = new ArrayList<DeviceInfo>();
		}
		
		/**Set interface parameter*/
		request.setAttribute("curPage", curPage); 
		request.setAttribute("pageCount", pageCount);
		
		request.setAttribute("deviceInfoList", list);
		request.setAttribute("devList", PushUtil.getDeviceList());
		
		return deviceList;
	}
	
	public void setServletRequest(HttpServletRequest request) {	
		this.request = request;
	}
	
	public void setServletResponse(HttpServletResponse rep) {
		this.response = rep;
		rep.setContentType("application/json; charset=utf-8");
		rep.setCharacterEncoding("utf-8");
		rep.setHeader("Cache-Control", "no-cache");
		rep.setDateHeader("Expires", 0);
	}
	
	/**
	 * According to the list of device serial numbers from pages,Create clear all data of device commands "CLEAR DATA" to whole device,which different serial number.
	 * @return
	 */
	public String clearAllData(){
		String sn = request.getParameter("sn");
		if (null != sn && !sn.isEmpty()) {
			String[] sns = sn.split(",");
			for (String deviceSn : sns) {
				ManagerFactory.getCommandManager().createClearAllDataCommand(deviceSn);
			}
		}
		return deviceList;
	}
	
	private void responseWebPage(String result) {
		try {
			PrintWriter out = response.getWriter();
			
			JSONObject resJson = new JSONObject();
			try {
				resJson.put("result", result);
			} catch (JSONException e) {
				e.printStackTrace();
			}
			out.write(resJson.toString());
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	/**
	 * According to the list of device serial numbers from pages,Create clear all data of device commands "CHECK" to whole device,which different serial number.
	 * If get checknew parameter from page,send CHECK command issued directly.Otherwise, Set 0 to the Stamp. eg.(Stamp = 0)
	 * @return
	 */
	public String checkDeviceData() {
		String sn = request.getParameter("sn");
		String checkNew = request.getParameter("checknew");
		if (null != sn && !"".equals(sn)) {
			String[] sns = sn.split(",");
			for (String deviceSn : sns) {
				if (null == checkNew) {
					ManagerFactory.getCommandManager().createCheckCommand(deviceSn, true);
				} else {
					ManagerFactory.getCommandManager().createCheckCommand(deviceSn, false);
				}
			}
			responseWebPage("success");
		} else {
			responseWebPage("error");
		}
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,Create clear all data of device commands "INFO" to whole device,which different serial number.
	 * @return
	 */
	public String checkDeviceInfo() {
		String sn = request.getParameter("sn");
		if (null != sn && !"".equals(sn)) {
			String[] sns = sn.split(",");
			for (String deviceSn : sns) {
				ManagerFactory.getCommandManager().createINFOCommand(deviceSn);
			}
		}
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,send the individual device data to they own device in the server.Corresponding to the "DATA UPDATE" command.
	 * @return
	 */
	public String restoreUserInfo() {
		logger.info("get restore request begin make cmd");
		final String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		for (final String deviceSn : sns) {
			new Thread(new Runnable() {
				public void run() {
					logger.info("begin make cmd:" + deviceSn);
					ManagerFactory.getCommandManager().createUpdateUserInfosCommandBySn(deviceSn, deviceSn);
					logger.info("end make cmd:" + deviceSn);
				}
			}).start();
		}
		responseWebPage("success");
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,Query attendance records from the specified device.
	 * Corresponding to the "DATA QUERY ATTLOG" command.
	 * @return
	 */
	public String queryAttLog() {
		final String sn = request.getParameter("sn");
		final String startTime = request.getParameter("startTime");
		final String endTime = request.getParameter("endTime");
		if (null == sn || sn.isEmpty() 
				|| null == startTime || startTime.isEmpty()
				|| null == endTime || endTime.isEmpty()) {
			return deviceList;
		}
		
		new Thread(new Runnable() {
			public void run() {
				String[] sns = sn.split(",");
				ManagerFactory.getCommandManager().createQueryAttLogCommand(startTime, endTime, sns);
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,Query attendance photo from the specified device.
	 * Corresponding to the "DATA QUERY ATTPHOTO" command.
	 * @return
	 */
	public String queryAttPhoto() {
		final String sn = request.getParameter("sn");
		final String startTime = request.getParameter("startTime");
		final String endTime = request.getParameter("endTime");
		if (null == sn || sn.isEmpty() 
				|| null == startTime || startTime.isEmpty()
				|| null == endTime || endTime.isEmpty()) {
			return deviceList;
		}
		new Thread(new Runnable() {
			public void run() {
				String[] sns = sn.split(",");
				ManagerFactory.getCommandManager().createQueryAttPhotoCommand(startTime, endTime, sns);
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,Query user infomation from the specified device.
	 * Corresponding to the "DATA QUERY USERINFO" command.
	 * @return
	 */
	public String queryUserInfo() {
		final String sn = request.getParameter("sn");
		final String userPin = request.getParameter("userPin");
		if (null == sn || null == userPin || sn.isEmpty() || userPin.isEmpty()) {
			return deviceList;
		}
		new Thread(new Runnable() {
			public void run() {
				String[] sns = sn.split(",");
				ManagerFactory.getCommandManager().createQueryUserInfoCommand(userPin, sns);
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,Query Fingerprint template from the specified device.
	 * Corresponding to the "DATA QUERY FINGERTMP" command.
	 * @return
	 */
	public String queryFingerTmp() {
		final String sn = request.getParameter("sn");
		final String userPin = request.getParameter("userPin");
		final String fingerId = request.getParameter("fingerId");
		if (null == sn || sn.isEmpty() 
				|| null == userPin || userPin.isEmpty()) {
			return deviceList;
		}
		new Thread(new Runnable() {
			public void run() {
				String[] sns = sn.split(",");
				ManagerFactory.getCommandManager().createQueryFingerTmpCommand(userPin, fingerId, sns);
			}
		}).start();
		return deviceList;
	}
	//abhi
	public String queryUpdateCommand() {
		final String sn = request.getParameter("sn");
		final String cmd = request.getParameter("cmd");
		
		if (null == sn || sn.isEmpty() || null == cmd || cmd.isEmpty()) {
			return deviceList;
		}
		new Thread(new Runnable() {
			public void run() {
				ManagerFactory.getCommandManager().updateUpdatequeryCommand(sn,cmd);
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * ä»Žæœ�åŠ¡å™¨ä¸­åˆ é™¤æŒ‡å®šè®¾å¤‡çš„ç”¨æˆ·ï¼ŒæŒ‡çº¹ï¼Œè€ƒå‹¤è®°å½•
	 * @return
	 */
	public String deleteServer() {
		String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		final String[] sns = sn.split(",");
		new Thread(new Runnable() {
			public void run() {
				ManagerFactory.getDeviceManager().deleteUserInfoByDeviceSn(sns);
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * Remove the device and all data related to the device from the server.
	 * @return
	 */
	public String deleteDevice() {
		String sn = request.getParameter("sn");
		if (null != sn && !"".equals(sn)) {
			final String[] sns = sn.split(",");
			new Thread(new Runnable() {
				public void run() {
					ManagerFactory.getDeviceManager().deleteDeviceBySn(sns);
				}
			}).start();
		}
		return deviceList;
	}
	
	/**
	 * Initialize the server.
	 * Delete all the data on the server.
	 * @return
	 */
	public String initDemo() {
		new Thread(new Runnable() {
			public void run() {
				ManagerFactory.getDeviceManager().initDemo();
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * Deletes all user data, including user information, fingerprint information, attendance records, attendance photo.
	 * @return
	 */
	public String deleteAllUserData() {
		new Thread(new Runnable() {
			public void run() {
				ManagerFactory.getDeviceManager().deleteAllUserData();
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,delete attendance records from the specified device.
	 * Corresponding to the "CLEAR LOG" command.
	 * @return
	 */
	public String clearAttLog() {
		String sn = request.getParameter("sn");
		if (null != sn && !"".equals(sn)) {
			String[] sns = sn.split(",");
			ManagerFactory.getCommandManager().createClearAttLogCommand(sns);
		}
		
		return deviceList;
	}

	/**
	 * According to the conditions of interface,delete attendance photo from the specified device.
	 * Corresponding to the "CLEAR PHOTO" command.
	 * @return
	 */
	public String clearPhoto() {
		String sn = request.getParameter("sn");
		if (null != sn && !"".equals(sn)) {
			String[] sns = sn.split(",");
			ManagerFactory.getCommandManager().createClearPhotoCommand(sns);
		}
		
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,reboot specified device.
	 * Corresponding to the "REBOOT" command.
	 * @return
	 */
	public String rebootDevice() {
		String sn = request.getParameter("sn");
		if (null != sn && !"".equals(sn)) {
			String[] sns = sn.split(",");
			ManagerFactory.getCommandManager().createRebootCommand(sns);
		}
		
		return deviceList;
	}
	
	/**
	 * According to the list of device serial numbers from pages,transfer data of source device in the data server to specified new device.
	 * Corresponding to the "DATA UPDATE" command.
	 * @return
	 */
	public String toNewDevice() {
		logger.info("get to new device request begin make cmd");
		final String sn = request.getParameter("sn");
		final String destSn = request.getParameter("destSn");
		if (null == sn || "".equals(sn) || null == destSn || "".equals(destSn)) {
			return deviceList;
		}
		String[] sns = destSn.split(",");
		for (final String deviceSn : sns) {
			new Thread(new Runnable() {
				public void run() {
					logger.info("begin make cmd:" + deviceSn);
					ManagerFactory.getCommandManager().createUpdateUserInfosCommandBySn(sn, deviceSn);
					logger.info("end make cmd:" + deviceSn);
				}
			}).start();
		}
		
		
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "shell" commnd to specified device.
	 * Corresponding to the "SHELL" command.
	 * @return
	 */
	public String shell() {
		String sn = request.getParameter("sn");
		String cmd = request.getParameter("cmd");
		if (null == sn || sn.isEmpty() || null == cmd || cmd.isEmpty()) {
			return deviceList;
		}
		cmd = cmd.replace(":", "&");
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createShellCommand(sns, cmd);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "option" parameter value to specified device.
	 * Corresponding to the "SET OPTION" command.
	 * @return
	 */
	public String setOption() {
		String sn = request.getParameter("sn");
		String option = request.getParameter("option");
		if (null == sn || sn.isEmpty() || null == option || option.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createSetOptionCommand(sns, option);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,reflash the parameter value to they own device.
	 * Corresponding to the "RELOAD OPTIONS" command.
	 * @return
	 */
	public String reloadOption() {
		String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createReloadOptionCommand(sns);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "LOG" commnd to specified device.
	 * @return
	 */
	public String logData() {
		String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createLogDataCommand(sns);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "AC_UNLOCK" commnd to specified device.
	 * @return
	 */
	public String unlock() {
		String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createUnlockCommand(sns);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "AC_UNALARM" commnd to specified device.
	 * @return
	 */
	public String unalarm() {
		String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createUnalarmCommand(sns);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "GetFile" commnd and file needs to be fetched from the device name to the specified device. 
	 * @return
	 */
	public String getFile() {
		String sn = request.getParameter("sn");
		String file = request.getParameter("file");
		if (null == sn || sn.isEmpty() || null == file || file.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createGetFileCommand(sns, file);
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "PutFile" commnd to the specified device and file name,file path needs to be sent to the device.
	 * @return
	 */
	public String putFile() {
		String sn = request.getParameter("sn");
		String srcFile = request.getParameter("srcFile");
		String destFile = request.getParameter("destFile");
		if (null == sn || sn.isEmpty() 
				|| null == srcFile || srcFile.isEmpty()
				|| null == destFile || destFile.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		ManagerFactory.getCommandManager().createPutFileCommand(sns, srcFile, destFile);
		return destFile;
	}
	
	/**
	 * According to the conditions of interface,send remote registration command to the specified device.
	 * Corresponding to the "ENROLL_FP" command.
	 * @return
	 */
	public String enrollFp() {
		String sn = request.getParameter("sn");
		String userPin = request.getParameter("userPin");
		String fingerIdStr = request.getParameter("fingerId");
		String retryTimesStr = request.getParameter("retryTimes");
		String isOverWriteStr = request.getParameter("isCover");
		if (null == sn || sn.isEmpty() 
				|| null == userPin || userPin.isEmpty()
				|| null == fingerIdStr || fingerIdStr.isEmpty()
				|| null == isOverWriteStr || isOverWriteStr.isEmpty()) {
			return deviceList;
		}
		String[] sns = sn.split(",");
		try {
			int fingerId = Integer.valueOf(fingerIdStr);
			int retryTimes = Integer.valueOf(retryTimesStr);
			int isOverWrite = Integer.valueOf(isOverWriteStr);
			ManagerFactory.getCommandManager().createEnrollFpCommand(userPin, fingerId, retryTimes, isOverWrite, sns);
		} catch (Exception e) {
			logger.error(e.toString());
		}
		return deviceList;
	}
	
	/**
	 * According to the conditions of interface,send "Attendance data proofread automatically" command to the specified device.
	 * Corresponding to the "VERIFY SUM ATTLOG" command.
	 * @return
	 */
	public String verifyLog() {
		final String sn = request.getParameter("sn");
		final String startTime = request.getParameter("startTime");
		final String endTime = request.getParameter("endTime");
		if (null == sn || sn.isEmpty() 
				|| null == startTime || startTime.isEmpty()
				|| null == endTime || endTime.isEmpty()) {
			return deviceList;
		}
		new Thread(new Runnable() {
			public void run() {
				String[] sns = sn.split(",");
				ManagerFactory.getCommandManager().createVerifySumAttLogCommand(sns, startTime, endTime);
			}
		}).start();
		return deviceList;
	}
	
	/**
	 * According to page requests, access to device information and go to the device editor page.
	 * @return
	 */
	public String editDevice() {
		String sn = request.getParameter("sn");
		if (null == sn || sn.isEmpty()) {
			return deviceList;
		}
		DeviceInfo deviceInfo = ManagerFactory.getDeviceManager().getDeviceInfoBySn(sn);
		if (null == deviceInfo) {
			return deviceList; 
		}
		String devFuns = deviceInfo.getDevFuns();
		StringBuilder sb = new StringBuilder();
		if (PushUtil.isDevFun(devFuns, DEV_FUNS.FP)) {
			sb.append("FP,");
		}
		if (PushUtil.isDevFun(devFuns, DEV_FUNS.FACE)) {
			sb.append("FACE,");
		} 
		if (PushUtil.isDevFun(devFuns, DEV_FUNS.USERPIC)) {
			sb.append("USERPIC,");
		}
		if (PushUtil.isDevFun(devFuns, DEV_FUNS.BIOPHOTO)) {
			sb.append("BIOPHOTO,");
		}
		if (PushUtil.isDevFun(devFuns, DEV_FUNS.BIODATA)) {
			sb.append("BIODATA,");
		}
		if (sb.length() > 0) {
			sb.deleteCharAt(sb.length() - 1);
		}
		deviceInfo.setDevFuns(sb.toString());
		request.setAttribute("deviceInfo", deviceInfo);
		
		List<DeviceAttrs> attrList = ManagerFactory.getAttrsManager().getDeviceAttrsBySn(sn);
		String cmdSize = "64";
		if (null != attrList) {
			for (DeviceAttrs deviceAttrs : attrList) {
				if (Constants.DEV_ATTR_CMD_SIZE.equals(deviceAttrs.getAttrName())) {
					if (null == deviceAttrs.getAttrValue() 
							|| deviceAttrs.getAttrValue().isEmpty()) {
						break;
					}
					cmdSize = deviceAttrs.getAttrValue();
					break;
				}
			}
		}
		
		request.setAttribute("cmdSize", cmdSize);
		return "edit";
	}
	
	/**
	 * According to the device information  came from interface,update device information.
	 * @return
	 */
	public String modifyDevice() {
		String deviceName = request.getParameter("devName");
		String transTimes = request.getParameter("transTimes");
		String transInterval = request.getParameter("transInterval");
		String deviceSn = request.getParameter("deviceSn");
		String cmdSize = request.getParameter("cmdSize");
		if (null == cmdSize || cmdSize.isEmpty()) {
			cmdSize = "64";
		}
		
		if (null == deviceSn || deviceSn.isEmpty()) {
			return "edit";
		}
		DeviceInfo deviceInfo = ManagerFactory.getDeviceManager().getDeviceInfoBySn(deviceSn);
		if (null == deviceInfo) {
			return "edit";
		}
		
		deviceInfo.setDeviceName(deviceName);
		deviceInfo.setTransTimes(transTimes);
		deviceInfo.setTransInterval(Integer.valueOf(transInterval));
		ManagerFactory.getDeviceManager().updateDeviceInfo(deviceInfo);
		
		DeviceAttrs attrs = new DeviceAttrs();
		attrs.setDeviceSn(deviceSn);
		attrs.setAttrName(Constants.DEV_ATTR_CMD_SIZE);
		attrs.setAttrValue(cmdSize);
		List<DeviceAttrs> list = new ArrayList<DeviceAttrs>();
		list.add(attrs);
		ManagerFactory.getAttrsManager().createDeviceAttrs(list);
		
		deviceInfo.setAttrList(list);
		PushUtil.updateDevMap(deviceInfo);
		
		return "deviceList";
	}
	
	/**
	 * Synchronize software data to device (user information/biometrics/photos/bioPothos) and advertisement
	 * @return
	 */
	public String syncDevice() {
		String sn = request.getParameter("sn");
		if (null != sn && !"".equals(sn)) {
			final String[] sns = sn.split(",");
			new Thread(new Runnable() {
				public void run() {
					ManagerFactory.getCommandManager().syncDevice(sns);
				}
			}).start();
		}
		return deviceList;
	}
}

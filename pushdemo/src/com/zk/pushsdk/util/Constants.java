package com.zk.pushsdk.util;

/**
 * Define all the constant value.
 * @author seiya
 *
 */

public final class Constants
{
	/**
	 * the label of file config.xml
	 */
	/**Configuration file name*/
	public static final String CONFIG_FILE_NAME = "config.xml";
	/**the URL of database connection*/
	public static final String DATABASE_URL = "databaseconnect.url";
	/**user name for database*/
    public static final String DATABASE_USER = "databaseconnect.user";
    /**password for database*/
    public static final String DATABASE_PWD = "databaseconnect.password";
    /**Driver for database*/
    public static final String DATABASE_DRIVER = "databaseconnect.driverclass";
    /**None*/
    @Deprecated
    public static final String OPTION_VER = "option.ver";
    /**the record count of a single page*/
    public static final String OPTION_PAGE_SIZE = "option.pagesize";
    /**the size of monitor data for a single page*/
    public static final String OPTION_MONIGOR_SIZE = "option.monitorsize";
    
    /**
     * the Server command and the constant value
     */
    /**Command header*/
    public static final String DEV_CMD_TITLE = "C:";
    /**SHELL command*/
    public static final String DEV_CMD_SHELL = "SHELL {0}";
    /**CHECK*/
    public static final String DEV_CMD_CHECK = "CHECK";
    /**CLEAR ATTENDANCE RECORD*/
    public static final String DEV_CMD_CLEAR_LOG = "CLEAR LOG";
    /**CLEAR ATTENDANCE PHOTO*/
    public static final String DEV_CMD_CLEAR_PHOTO = "CLEAR PHOTO";
    /**CLEAR ALL DATA*/
    public static final String DEV_CMD_CLEAR_DATA = "CLEAR DATA";
    /**SEND DEVICE INFO TO SERVER*/
    public static final String DEV_CMD_INFO = "INFO";
    /**SET DEVICE OPTION*/
    public static final String DEV_CMD_SET_OPTION = "SET OPTION {0}";
    /**REBOOT DEVICE*/
    public static final String DEV_CMD_REBOOT = "REBOOT";
    /**UPDATE USER INFO*/
    public static final String DEV_CMD_DATA_UPDATE_USERINFO = "DATA UPDATE USERINFO PIN={0}\tName={1}\tPri={2}\tPasswd={3}\tCard={4}\tGrp={5}\tTZ={6}\tCategory={7}";
    /**UPDATE FP TEMPLATE*/
    public static final String DEV_CMD_DATA_UPDATE_FINGER = "DATA UPDATE FINGERTMP PIN={0}\tFID={1}\tSize={2}\tValid={3}\tTMP={4}";
    /**UPFATE FACE TEMPLATE*/
    public static final String DEV_CMD_DATA_UPDATE_FACE = "DATA UPDATE FACE PIN={0}\tFID={1}\tSize={2}\tValid={3}\tTMP={4}";
    /**UPDATE USER PHOTO*/
    public static final String DEV_CMD_DATA_UPDATE_USERPIC = "DATA UPDATE USERPIC PIN={0}\tSize={1}\tContent={2}";
    /**UPDATE BIOPHOTO*/
    public static final String DEV_CMD_DATA_UPDATE_BIOPHOTO = "DATA UPDATE BIOPHOTO PIN={0}\tType={1}\tSize={2}\tContent={3}";
    /**UPDATE SMS*/
    public static final String DEV_CMD_DATA_UPDATE_SMS = "DATA UPDATE SMS MSG={0}\tTAG={1}\tUID={2}\tMIN={3}\tStartTime={4}";
    /**UPDATE USER SMS*/
    public static final String DEV_CMD_DATA_UPDATE_USER_SMS = "DATA UPDATE USER_SMS PIN={0}\tUID={1}";
    /**DELETE USER INFO*/
    public static final String DEV_CMD_DATA_DELETE_USERINFO = "DATA DELETE USERINFO PIN={0}";
    /**DELETE FP TEMPLATE*/
    public static final String DEV_CMD_DATA_DELETE_FINGER = "DATA DELETE FINGERTMP PIN={0}\tFID={1}";
    /**DELETE FACE TEMPLATE*/
    public static final String DEV_CMD_DATA_DELETE_FACE = "DATA DELETE FACE PIN={0}\tFID={1}";
    /**DELETE USER PHOTO*/
    public static final String DEV_CMD_DATA_DELETE_USERPIC = "DATA DELETE USERPIC PIN={0}";
    /**CLEAR USER*/
    public static final String DEV_CMD_DATA_CLEAR_USERINFO = "CLEAR ALL USERINFO";
    /**DELETE SMS*/
    public static final String DEV_CMD_DATA_DELETE_SMS = "DATA DELETE SMS UID={0}";
    /**QUERY ATTENDANCE RECORD*/
    public static final String DEV_CMD_DATA_QUERY_ATTLOG = "DATA QUERY ATTLOG StartTime={0}\tEndTime={1}";
    /**QUERY ATTENDANCE PHOTO*/
    public static final String DEV_CMD_DATA_QUERY_ATTPHOTO = "DATA QUERY ATTPHOTO StartTime={0}\tEndTime={1}";
    /**QUERY USER INFO*/
    public static final String DEV_CMD_DATA_QUERY_USERINFO = "DATA QUERY USERINFO PIN={0}";
    /**QUERY FP BY USER AND FINGER INDEX*/
    public static final String DEV_CMD_DATA_QUERY_FINGERTMP = "DATA QUERY FINGERTMP PIN={0}\tFID={1}";
    /**QUERY FP BY USER ID*/
    public static final String DEV_CMD_DATA_QUERY_FINGERTMP_ALL = "DATA QUERY FINGERTMP PIN={0}";
    /**ONLINE ENROLL USER FP*/
    public static final String DEV_CMD_ENROLL_FP = "ENROLL_FP PIN={0}\tFID={1}\tRETRY={2}\tOVERWRITE={3}";
    /**CHECK AND SEND LOG*/
    public static final String DEV_CMD_LOG = "LOG";
    /**UNLOCK THE DOOR*/
    public static final String DEV_CMD_AC_UNLOCK = "AC_UNLOCK";
    /**CLOSE THE ALARM*/
    public static final String DEV_CMD_AC_UNALARM = "AC_UNALARM";
    /**GET FILE*/
    public static final String DEV_CMD_GET_FILE = "GetFile {0}";
    /**SEND FILE*/
    public static final String DEV_CMD_PUT_FILE = "PutFile {0}\t{1}";
    /**RELOAD DEVICE OPTION*/
    public static final String DEV_CMD_RELOAD_OPTIONS = "RELOAD OPTIONS";
    /**AUTO PROOFREAD ATTENDANCE RECORD*/
    public static final String DEV_CMD_VERIFY_SUM_ATTLOG = "VERIFY SUM ATTLOG StartTime={0}\tEndTime={1}";    
    /**UPDATE MEET INFO*/
    public static final String DEV_CMD_DATA_UPDATE_MEETINFO = "DATA UPDATE MEETINFO MetName={0}\tMetStarSignTm={1}\tMetLatSignTm={2}\tEarRetTm={3}\tLatRetTm={4}\tCode={5}\tMetStrTm={6}\tMetEndTm={7}";
    /**DELETE MEET INFO*/
    public static final String DEV_CMD_DATA_DELETE_MEETINFO = "DATA DELETE MEETINFO Code={0}";
    /**UPDATE PERS MEET*/
    public static final String DEV_CMD_DATA_UPDATE_PERSMEET = "UPDATE PERSMEET Code={0}\tPin={1}";
    /**PutAdvFile*/
    public static final String DEV_CMD_DATA_UPDATE_ADV = "PutAdvFile Type={0}\tFileName={1}\tUrl=downloadFile?SN={2}&path={3}";
    /**DelAdvFile*/
    public static final String DEV_CMD_DATA_DELETE_ADV = "DelAdvFile Type={0}\tFileName={1}";
    /**CLEAR ADV FILE*/
    public static final String DEV_CMD_DATA_CLEAR_ADV = "DelAdvFile Type={0}";
    /**CLEAR MEET INFO*/
    public static final String DEV_CMD_DATA_CLEAR_MEET = "CLEAR MEETINFO";
    /**CLEAR PERSMEET INFO*/
    public static final String DEV_CMD_DATA_CLEAR_PERSMEET = "CLEAR PERSMEET";
    
    
    public static final String DEV_TABLE_ATTLOG = "ATTLOG";
    public static final String DEV_TABLE_OPLOG = "OPERLOG";
    public static final String DEV_TABLE_ATTPHOTO = "ATTPHOTO";
    public static final String DEV_TABLE_SMS = "SMS";
    public static final String DEV_TABLE_USER_SMS = "USER_SMS";
    public static final String DEV_TABLE_USERINFO = "USERINFO";
    public static final String DEV_TABLE_FINGER_TMP = "FINGERTMP";
    public static final String DEV_TABLE_FACE = "FACE";
    public static final String DEV_TABLE_USERPIC = "USERPIC";
    
    /**
     *Biometric template type
     */
    public static final int BIO_TYPE_GM = 0;//generic
    public static final int BIO_TYPE_FP = 1;//finger print
    public static final int BIO_TYPE_FACE = 2;//FACE
    public static final int BIO_TYPE_VOICE = 3;//VOICE
    public static final int BIO_TYPE_IRIS = 4;//IRIS
    public static final int BIO_TYPE_RETINA = 5;//RETINA
    public static final int BIO_TYPE_PP = 6;//palm print
    public static final int BIO_TYPE_FV = 7;//finger-vein
    public static final int BIO_TYPE_PALM = 8;//PALM
    public static final int BIO_TYPE_VF = 9;//visible face
    
    /**
     * Biometric algorithm version
     */
    public static final String BIO_VERSION_FP_9 = "9.0";//Finger print version: 9.0
    public static final String BIO_VERSION_FP_10 = "10.0";//Finger print version: 10.0
    public static final String BIO_VERSION_FP_12 = "12.0";//Finger print version: 10.0
    public static final String BIO_VERSION_FACE_5 = "5.0";//Face version: 5.0
    public static final String BIO_VERSION_FACE_7 = "7.0";//Face version: 7.0
    public static final String BIO_VERSION_FV_3 = "3.0";
    
    /**
     * Biometric data format
     */
    public static final int BIO_DATA_FMT_ZK = 0;//ZK format
    public static final int BIO_DATA_FMT_ISO = 1;//ISO format
    public static final int BIO_DATA_FMT_ANSI = 2;//ANSI format
    
    /**
     * Language Encoding
     */
    public static final String DEV_LANG_ZH_CN = "83";//chinese
    
    public static final String DEV_ATTR_CMD_SIZE = "CMD_SIZE";
    
    /**
     * support feature
     * @author seiya
     *
     */
    public static enum DEV_FUNS {
    	FP,
    	FACE, 
    	USERPIC,
    	BIOPHOTO,
    	BIODATA
    };
    
    
}

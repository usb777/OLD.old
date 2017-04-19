/*
 * NewServlet.java
 *
 * Created on 24 Март 2007 г., 15:16
 */

import java.io.*;
import java.net.*;

import javax.servlet.*;
import javax.servlet.http.*;
import java.sql.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;


/**
 *
 * @author IntD
 * @version
 */
public class NewServlet extends HttpServlet {
    
    /** Processes requests for both HTTP <code>GET</code> and <code>POST</code> methods.
     * @param request servlet request
     * @param response servlet response
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
    throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = response.getWriter();
        /* TODO output your page here
        out.println("<html>");
        out.println("<head>");
        out.println("<title>Servlet NewServlet</title>");
        out.println("</head>");
        out.println("<body>");
        out.println("<h1>Servlet NewServlet at " + request.getContextPath () + "</h1>");
        out.println("</body>");
        out.println("</html>");
         */
           Connection conn = null;  
            final String hostname = "localhost"; 
            final String port = "3306"; 
            final String dbname = "beltapaz"; 
            final String user = ""; 
            final String password = ""; 
           
            
           //Load the JDBC driver
           try 
           { 
           	Class.forName("com.mysql.jdbc.Driver").newInstance(); 
           } 
           catch (Exception e) 
           { 
                System.err.println("Unable to load driver."); 
                e.printStackTrace(); 
           } 
           try 
           { 
        	 //  System.out.println("* Establish a connection"); 
        	   String url = "jdbc:mysql://"+hostname+":"+port+"/"+dbname; 
                   conn = DriverManager.getConnection(url, user, password);  
           } 
           catch (SQLException sqle)
           { 
	            System.out.println("SQLException: " + sqle.getMessage()); 
	            System.out.println("SQLState: " + sqle.getSQLState()); 
	            System.out.println("VendorError: " + sqle.getErrorCode()); 
	            sqle.printStackTrace();
	            System.exit(1);
	   }
            
      Statement stmt = null;
      ResultSet rs = null;
       	             	       
      
     try {
      
      // Создаем объект-выражение
      stmt = conn.createStatement();
      // Выполняем запрос к базе данных
      rs = stmt.executeQuery("SELECT * FROM ipnet");
      // Выводим на консоль значения 2-х полей полученных записей
      while (rs.next()) 
      {
        out.println(rs.getString("ip")+"&nbsp;&nbsp;&nbsp;&nbsp;"+rs.getString("referrer")+"&nbsp;&nbsp;&nbsp;&nbsp;"+rs.getString("dtime"));
        out.println("<br>");
      }
    }
     catch (Exception e) 
    {
      e.printStackTrace(System.err);
    } 
    finally {
      try {
        if (rs != null) { rs.close(); }
        if (stmt != null) { stmt.close(); }
        if (conn != null) { conn.close(); }
      } catch (SQLException e) 
      {
        e.printStackTrace(System.err);
      }
    }
  

            
            
        out.close();
     
        
        
        
    }
    
    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /** Handles the HTTP <code>GET</code> method.
     * @param request servlet request
     * @param response servlet response
     */
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
    throws ServletException, IOException {
        processRequest(request, response);
    }
    
    /** Handles the HTTP <code>POST</code> method.
     * @param request servlet request
     * @param response servlet response
     */
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
    throws ServletException, IOException {
        processRequest(request, response);
    }
    
    /** Returns a short description of the servlet.
     */
    public String getServletInfo() {
        return "Short description";
    }
    // </editor-fold>
    
    public void JConnector(Connection conn)
    {     
            
    }//end of method

    private javax.sql.DataSource getMysqlbaz() throws javax.naming.NamingException {
        javax.naming.Context c = new javax.naming.InitialContext();
        return (javax.sql.DataSource) c.lookup("java:comp/env/jdbc/mysqlbaz");
    }
}

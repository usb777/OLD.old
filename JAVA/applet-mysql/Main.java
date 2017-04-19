/*
 * Main.java
 *
 * Created on 9 Апрель 2007 г., 12:40
 *
 * To change this template, choose Tools | Template Manager
 * and open the template in the editor.
 */

package javaapplication4;
import java.sql.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;


/**
 *
 * @author IntD
 */
public class Main {
    
    /** Creates a new instance of Main */
    public Main() {
    }
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) 
    {
        // TODO code application logic here
        System.out.print("Hail!!!");
        
        
            
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
        System.out.println(rs.getString("ip")+" "+rs.getString("referrer")+" "+rs.getString("dtime"));
        //out.println("<br>");
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
  
    }
    
}

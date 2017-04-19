/*
 * applet.java
 *
 * Created on 9 јпрель 2007 г., 13:08
 *
 * To change this template, choose Tools | Template Manager
 * and open the template in the editor.
 */

package javaapplication4;
import java.awt.*;
import java.awt.event.*;
import java.applet.*;
import java.sql.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.*;

/**
 *
 * @author IntD
 */
public class applet extends java.applet.Applet {
        
       
      
         String hostname = "localhost"; 
          String port = "3306"; 
          String dbname = "beltapaz"; 
          String user = "beltapaz"; 
         String password = "marketing43"; 
         private Vector results;
      private String url = "jdbc:mysql://"+hostname+":"+port+"/"+dbname; 
    /** Initialization method that will be called after the applet is loaded
     *  into the browser.
     */
    public void init()
    {    try 
           { // DriverManager.registerDriver(new com.mysql.jdbc.Driver());
       
           	Class.forName("com.mysql.jdbc.Driver").newInstance(); 
           } 
           catch (Exception e) 
           { 
                System.err.println("Unable to load driver."); 
                e.printStackTrace(); 
           } 
 
    
         Myconnect();
         
         
    }
    
   public void run()
   {
       
       
           
   }
 
    public void paint(Graphics g)
    {
     
   
     if (results==null)
     {g.drawString("Net zaprosa",200,100);
       g.drawRect(100,100,300,400);
      return; 
     }
   
  else { 
    int y=30,n=results.size();
     for (int i=1;i<n;i++)
         g.drawString((String)results.elementAt(i),50,y+=20);
    }
    
  
       
    // TODO overwrite start(), stop() and destroy() methods
   
   }
    
    public void Myconnect()
    {
        try {
        
      Connection conn = DriverManager.getConnection(url, user, password);  
      Statement st = conn.createStatement();;
      ResultSet rs = st.executeQuery("SELECT * FROM ipnet");  
      ResultSetMetaData rsmd = rs.getMetaData();
      int n=rsmd.getColumnCount();
     results = new Vector();
     rs.first();
      while (rs.next()) 
      {
     String s=" ";
     for (int i=1;i<=n;i++)
         s+=" "+rs.getObject(i);
         results.addElement(s);       
       } //while
      
  rs.close();
   st.close();
   conn.close();
   repaint();  
     }catch (Exception e) 
       {System.err.println(e);
     }
    
      repaint(); 
         
     
    }

    
    
}

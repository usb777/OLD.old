/*
 * NewApplet.java
 *
 * Created on 9 јпрель 2007 г., 12:43
 *
 * To change this template, choose Tools | Template Manager
 * and open the template in the editor.
 */

package javaapplication4;
import java.awt.*;
import java.applet.*;
/*import java.sql.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
*/
/**
 *
 * @author IntD
 */
    /** Initialization method that will be called after the applet is loaded
     *  into the browser.
     */
    
     
public class NewApplet extends java.applet.Applet 
{
         

    public void init() 
    {

     
     
        
        // TODO start asynchronous download of heavy resources
    }
    
  public void paint(Graphics g)
  { // g.draw3DRect(100,100,200,200,true);
     g.drawString("New database",100,100);
 //    g.setColor(Color.BLUE);
   
  }
    
    // TODO overwrite start(), stop() and destroy() methods
}

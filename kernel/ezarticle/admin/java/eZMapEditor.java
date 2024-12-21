// 
// $Id: eZMapEditor.java 5818 2001-06-19 10:32:18Z jhe $
//
// Jo Henrik Endrerud <jhe@ez.no>
// Created on: <12-Jun-2001 10:33:52 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

import java.applet.Applet;
import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import java.net.*;
import java.util.Vector;
import java.util.StringTokenizer;

public class eZMapEditor extends Applet
{
    private final String Delimiter = "|";

    private String Filename;
    private Image Imagefile;
    private List LinkList;
    private Vector LinkVector;
    private TextField LinkText;
    private TextField AltText;
    private eZMapEditorImagePanel ImagePanel;
    private Choice ShapeChooser;
    
    public void init()
    {
        URL imageURL;
        Filename = getParameter( "Image" );
        imageURL = getCodeBase();
        Imagefile = getImage( imageURL, Filename );
        LinkVector = new Vector();
        LinkList = new List();
        createGUI();

        boolean done = false;
        String element = new String();
        int i = 0;
        
        while ( ! done )
        {
            String link;
            String alt;
            int shape;
            Point start;
            Point end;
            element = getParameter( "Element" + i );

            if ( element == null )
       	    {
                done = true;
            }
            else
            {
                StringTokenizer tokens = new StringTokenizer( element, Delimiter );
                link = tokens.nextToken();
                alt = tokens.nextToken();
                shape = Integer.parseInt( tokens.nextToken() );
                int x, y;
                x = Integer.parseInt( tokens.nextToken() );
                y = Integer.parseInt( tokens.nextToken() );
                start = new Point( x, y );    
                x = Integer.parseInt( tokens.nextToken() );
                y = Integer.parseInt( tokens.nextToken() );
                end = new Point( x, y );
                addItem( link, alt, shape, start, end );
            }
            i++;
        }
    }
    
    public void createGUI()
    {
        setBackground( Color.white );
        setLayout( new BorderLayout() );
        
        Panel controlpanel = new Panel( new GridLayout( 0, 1, 0, 0 ) );
        ScrollPane scrollpane = new ScrollPane( ScrollPane.SCROLLBARS_ALWAYS );
        ImagePanel = new eZMapEditorImagePanel( Imagefile );
        
        scrollpane.add( ImagePanel );
        
        ShapeChooser = new Choice();
        ShapeChooser.addItemListener( new eZMapEditorShapeListener( ImagePanel, ShapeChooser ) );
        ShapeChooser.add( "Rectangle" );
        ShapeChooser.add( "Circle" );
        
        LinkText = new TextField( "", 30 );
        AltText = new TextField( "", 30 );
        
        Panel buttons = new Panel( new GridLayout( 1, 2 ) );
        Button addbutton = new Button( "Add" );
        Button clearbutton = new Button( "Clear" );
        
        addbutton.addActionListener( new eZMapEditorAddListener( this ) );
        clearbutton.addActionListener( new eZMapEditorClearListener( this ) );
        
        buttons.add( addbutton );
        buttons.add( clearbutton );
        
        Panel extrabuttons = new Panel( new FlowLayout( FlowLayout.LEFT, 0, 0 ) );
        
        Panel listpanel = new Panel( new BorderLayout() );
        
        LinkList.addItemListener( new eZMapEditorListListener( this ) );
        Button delete = new Button( "Remove" );
        delete.addActionListener( new eZMapEditorDeleteListener( this ) );

        extrabuttons.add( delete );

        controlpanel.add( new Label( "Shape:" ) );
        controlpanel.add( ShapeChooser );
        controlpanel.add( new Label( "Link:" ) );
        controlpanel.add( LinkText );
        controlpanel.add( new Label( "Alt text:" ) );
        controlpanel.add( AltText );
        controlpanel.add( buttons );

        GridBagLayout gridbag = new GridBagLayout();
        GridBagConstraints c = new GridBagConstraints();
        
        Panel bottom = new Panel();
        bottom.setLayout( gridbag );

        c.fill = GridBagConstraints.HORIZONTAL;
        c.insets = new Insets( 5, 0, 5, 0 );
        c.gridwidth = GridBagConstraints.REMAINDER;
        c.weightx = 1.0;

        gridbag.setConstraints( controlpanel, c );
        bottom.add( controlpanel );
        
        gridbag.setConstraints( LinkList, c );
        bottom.add( LinkList );
        
        gridbag.setConstraints( extrabuttons, c );
        bottom.add( extrabuttons );
        
        add( "Center", scrollpane );
        add( "South", bottom );
    }

    public void addItem()
    {
        if ( LinkText.getText().length() > 0 && ImagePanel.isSet() )
        {
            Point start = new Point( ImagePanel.StartPoint.x, ImagePanel.StartPoint.y );
            Point end = new Point( ImagePanel.EndPoint.x, ImagePanel.EndPoint.y );
            addItem( LinkText.getText(), AltText.getText(), ShapeChooser.getSelectedIndex(), start, end );
            clear();
        }
    }

    public void addItem( String link, String alt, int shape, Point start, Point end )
    {
        LinkVector.addElement( new eZMapEditorElement( link, alt, shape, start, end ) );
        LinkList.add( link );
    }

    public void deleteItem()
    {
        int selected = LinkList.getSelectedIndex();
        if ( selected > -1 )
        {
            LinkList.remove( selected );
            LinkVector.removeElementAt( selected );
            clear();
        }
    }

    public void clear()
    {
        LinkText.setText( "" );
        AltText.setText( "" );
        ImagePanel.clear();
        LinkText.requestFocus();
    }

    public String getAllElements()
    {
        String returnString = new String();
        eZMapEditorElement element;
        for ( int i = 0; i < LinkVector.size(); i++ )
        {
            element = (eZMapEditorElement)LinkVector.elementAt( i );
            returnString = returnString + element.getLink() + Delimiter;
            returnString = returnString + element.getAlt() + Delimiter;
            returnString = returnString + element.getShape() + Delimiter;
            returnString = returnString + element.getStartPos().x + Delimiter;
            returnString = returnString + element.getStartPos().y + Delimiter;
            returnString = returnString + element.getEndPos().x + Delimiter;
            returnString = returnString + element.getEndPos().y + "\n";
        }
        return returnString;
    }
    
    public void changeItem()
    {
        eZMapEditorElement element = (eZMapEditorElement)LinkVector.elementAt( LinkList.getSelectedIndex() );
        LinkText.setText( element.getLink() );
        AltText.setText( element.getAlt() );
        ShapeChooser.select( element.getShape() );
        ImagePanel.setShape( element.getShape() );
        ImagePanel.StartPoint.setLocation( element.getStartPos().x , element.getStartPos().y );
        ImagePanel.EndPoint.setLocation( element.getEndPos().x, element.getEndPos().y );
        ImagePanel.repaint();
    }

    public String getAppletInfo()
    {
        return "eZImageMap version 1.00 by eZ systems";
    }
}

class eZMapEditorClearListener implements ActionListener
{
    private eZMapEditor Parent;
    
    eZMapEditorClearListener( eZMapEditor parent )
    {
        Parent = parent;
    }

    public void actionPerformed( ActionEvent e )
    {
        Parent.clear();
    }
}

class eZMapEditorDeleteListener implements ActionListener
{
    private eZMapEditor Parent;

    eZMapEditorDeleteListener( eZMapEditor parent )
    {
        Parent = parent;
    }
    
    public void actionPerformed( ActionEvent e )
    {
        Parent.deleteItem();
    }
}

class eZMapEditorListListener implements ItemListener
{
    private eZMapEditor Parent;

    eZMapEditorListListener( eZMapEditor parent )
    {
        Parent = parent;
    }

    public void itemStateChanged( ItemEvent e )
    {
        Parent.changeItem();
    }    
}

class eZMapEditorAddListener implements ActionListener
{
    private eZMapEditor Parent;
    
    eZMapEditorAddListener( eZMapEditor parent )
    {
        Parent = parent;
    }

    public void actionPerformed( ActionEvent e )
    {
        Parent.addItem();
    }
}

class eZMapEditorElement
{
    private String Link;
    private String Alt;
    private int Shape;
    private Point StartPos;
    private Point EndPos;
    
    eZMapEditorElement( String link, String alt, int shape, Point start, Point end )
    {
        Link = link;
        Alt = alt;
        Shape = shape;
        StartPos = start;
        EndPos = end;
    }

    public String getLink()
    {
        return Link;
    }

    public String getAlt()
    {
        return Alt;
    }

    public int getShape()
    {
        return Shape;
    }

    public Point getStartPos()
    {
        return StartPos;
    }

    public Point getEndPos()
    {
        return EndPos;
    }
}

class eZMapEditorShapeListener implements ItemListener
{
    eZMapEditorImagePanel ImagePanel;
    Choice ParentChoice;
    
    eZMapEditorShapeListener( eZMapEditorImagePanel panel, Choice parent )
    {
        ImagePanel = panel;
        ParentChoice = parent;
    }

    public void itemStateChanged( ItemEvent e )
    {
        ImagePanel.setShape( ParentChoice.getSelectedIndex() );
    }
}

class eZMapEditorImagePanel extends Canvas implements MouseMotionListener, MouseListener, ImageObserver
{
    public Point StartPoint = new Point( 0, 0 );
    public Point EndPoint = new Point( 0, 0 );

    private Image Imagefile;
    private Image Backbuffer;
    private int Shape = 0;
    Dimension Dim = new Dimension( 100, 100 );
    
    eZMapEditorImagePanel( Image image )
    {
        Imagefile = image;
        Backbuffer = Imagefile;
        int x, y;
        x = Imagefile.getWidth( this );
        y = Imagefile.getHeight( this );
        if ( x >= 0 && y >= 0 )
        {
            Dim = new Dimension( x, y );
            setSize( Dim );
        }
        addMouseMotionListener( this );
        addMouseListener( this );
    }

    public boolean imageUpdate( Image img, int infoflags, int x, int y, int width, int height )
    {
        if ( width >= 0 && height >= 0 )
        {
            Dim = new Dimension( width, height );
            setSize( Dim );
        }
        return true;
    }

    public boolean isSet()
    {
        if ( StartPoint.x == 0 && StartPoint.y == 0 && EndPoint.x == 0 && EndPoint.y == 0 )
            return false;
        else
            return true;
    }
    
    public void clear()
    {
        EndPoint.setLocation( 0, 0 );
        StartPoint.setLocation( 0, 0 );
        repaint();
    }
    
    public void paint( Graphics g )
    {
        g.drawImage( Backbuffer, 0, 0, this );
        setSize( Dim );
        Image buffer;
        Graphics offscreen;
        
        buffer = createImage( Dim.width, Dim.height );
        offscreen = buffer.getGraphics();
        
        offscreen.drawImage( Imagefile, 0, 0, this );

        offscreen.setXORMode( Color.white );
        switch ( Shape )
        {
            case 0:
            {
                offscreen.drawRect( Math.min( StartPoint.x, EndPoint.x ),
                                    Math.min( StartPoint.y, EndPoint.y ),
                                    Math.max( EndPoint.x - StartPoint.x, StartPoint.x - EndPoint.x ),
                                    Math.max( EndPoint.y - StartPoint.y, StartPoint.y - EndPoint.y ) );
            }
            break;

            case 1:
            {
                if ( EndPoint.x > EndPoint.y )
                {
                    offscreen.drawOval( StartPoint.x, StartPoint.y,
                                        EndPoint.x - StartPoint.x,
                                        EndPoint.x - StartPoint.x );
                }
                else
                {
                    offscreen.drawOval( StartPoint.x, StartPoint.y,
                                        EndPoint.y - StartPoint.y,
                                        EndPoint.y - StartPoint.y );
                }
            }
            break;
        }
        offscreen.setPaintMode();
        g.drawImage( buffer, 0, 0, this );
        Backbuffer = buffer;
    }
    
    public void setShape( int shape )
    {
        Shape = shape;
        EndPoint.setLocation( 0, 0 );
        StartPoint.setLocation( 0, 0 );
        repaint();
    }
    
    public void mouseDragged( MouseEvent e )
    {
        EndPoint.setLocation( e.getX(), e.getY() );
        repaint();
    }

    public void mouseMoved( MouseEvent e )
    {

    }

    public void mouseClicked( MouseEvent e )
    {

    }

    public void mouseEntered( MouseEvent e )
    {

    }

    public void mouseExited( MouseEvent e )
    {

    }

    public void mousePressed( MouseEvent e )
    {
        StartPoint.setLocation( e.getX(), e.getY() );
    }

    public void mouseReleased( MouseEvent e )
    {
        EndPoint.setLocation( e.getX(), e.getY() );
        repaint();
    }
}

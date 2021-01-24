import React from "react";
import {
  Row,
  Card,
  CardBody,
  CardSubtitle,
  CardImg,
  CardText,
  CustomInput,
  CardTitle,
  Badge,
  Button
} from "reactstrap";
import { NavLink } from "react-router-dom";
import { ContextMenuTrigger } from "react-contextmenu";
import { Colxx } from "../../components/common/CustomBootstrap";




const ImageListView = ({ product, collect }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="3" className="mb-3" key={product.slug}>
      
      <ContextMenuTrigger id="menu_id" data={product.id} collect={collect}>
      
        <Card
        
        >
         
          
          
          <CardBody>
          <NavLink to={'/ders/'+product.slug}>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
               
            <CardTitle style={{textAlign:'center'}}>{product.lessonName}</CardTitle>

            <CardSubtitle style={{textAlign:'center'}}>
            <Button className="b-3">
              Ders İçeriği
            </Button>
            </CardSubtitle>
            
            
              </Colxx>
              
            </Row>
            </NavLink>
           
          </CardBody>
            
        </Card>
       
     
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(ImageListView);

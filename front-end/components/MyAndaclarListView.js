import React from "react";
import {
  Row,
  Card,
  CardBody,
  CardSubtitle,
  CardImg,
  CardText,
  CustomInput,
  Badge,
  Button
} from "reactstrap";
import { NavLink } from "react-router-dom";
import classnames from "classnames";
import { ContextMenuTrigger } from "react-contextmenu";
import { Colxx } from "../../components/common/CustomBootstrap";
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey



const ImageListView = ({ product, collect, route }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="3" className="mb-3" key={product.id}>
      
      <ContextMenuTrigger id="menu_id" data={product.id} collect={collect}>
      
        <Card
         
        >
         
          
          
          <CardBody>
          <CardSubtitle>{product.writerUser.name} {product.writerUser.surname}</CardSubtitle>
          <NavLink to={route.url+'/'+product.id}>

            <hr className="my-1" />
            </NavLink>

 
               
            <NavLink to={route.url+'/'+product.id}>
                <Button>
             Andaç'ı Oku
            </Button>
                </NavLink>
                
          </CardBody>
          
        </Card>
       
     
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(ImageListView);

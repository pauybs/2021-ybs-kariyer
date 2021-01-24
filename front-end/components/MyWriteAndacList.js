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



const ImageListView = ({ product, collect,route }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="3" className="mb-3" key={product.id}>
      
      <ContextMenuTrigger id="menu_id" data={product.id} collect={collect}>
      
        <Card
         
        >
         
          
          
          <CardBody>
          <CardSubtitle>{product.ownerUser.name} {product.ownerUser.surname}</CardSubtitle>
          İlan Başlığı :
          <NavLink to={route.url+'/'+product.id}>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
             
              <CardSubtitle> {product.university.universityName}</CardSubtitle>
            
               
              </Colxx>
              
            </Row>
            <hr className="my-1" />
            </NavLink>
            
               
                {
                  localStorage.getItem('user') && JSON.parse(localStorage.getItem('user')).username == product.writerUser.username ?
            <NavLink  to={route.url+'/'+product.id}>
                <Button>
             Andaç Güncelle
            </Button>
                </NavLink>
                  :null
                }
          </CardBody>
          
        </Card>
       
     
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(ImageListView);

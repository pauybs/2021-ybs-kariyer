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



const ImageListView = ({ product, collect }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="3" className="mb-3" key={product.slug}>
      
      <ContextMenuTrigger id="menu_id" data={product.id} collect={collect}>
      
        <Card
         
        >
         
          
         
          <CardBody>
          <CardSubtitle> {product.status == 0 ? "Sorunuz Değerlendiriliyor":"Soru Reddedildi"}</CardSubtitle>
          Soru Başlığı :
          <NavLink to={'/soru-guncelle/'+product.slug}>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
             
              <CardSubtitle> {product.questionTitle}</CardSubtitle>
               
               
              </Colxx>
              
            </Row>
            <hr className="my-1" />
            </NavLink>
            Soru Sahibi :
  <a href={'/profil/@'+product.user.username} target="_blank"> <p>{product.user.name} {product.user.surname} <br></br>@{product.user.username}</p> </a> <CardText className="text-muted text-small mb-0 font-weight-light">
            {Moment(product.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(product.createdAt.date).startOf('hour').fromNow()
            }</p>
                </CardText>
                {
                  localStorage.getItem('user') && JSON.parse(localStorage.getItem('user')).username == product.user.username && product.status == 0 ?
            <NavLink to={"/soru-guncelle/"+product.slug}>
                <Button>
              Güncelle
            </Button>
                </NavLink>
                  :
                  <Button>
                  Soru Reddedildi 3 Gün İçinde Silinecek
                </Button>
                }
                
              
          </CardBody>
         
        </Card>
           
     
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(ImageListView);

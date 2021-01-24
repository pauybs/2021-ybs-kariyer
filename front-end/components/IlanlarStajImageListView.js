import React from "react";
import {
  Row,
  Card,
  CardBody,
  CardSubtitle,
  CardImg,
  CardText,
  CustomInput,
  Badge
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
          <CardSubtitle>İlan Tipi : {product.internType === true ? "Staj Yeri Arıyorum" : "Stajyer Öğrenci Arıyorum"}</CardSubtitle>
          İlan Başlığı :
          <NavLink to={'/staj-ilani/'+product.slug}>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
             
              <CardSubtitle> {product.internTitle}</CardSubtitle>
            
               
              </Colxx>
              
            </Row>
            <hr className="my-1" />
            </NavLink>
            {product.internType === false ? <CardSubtitle>Şirket : {product.internCompany}</CardSubtitle> : null}
            {product.internType === false ? <CardSubtitle>Şirket Sektörü : {product.workplaceSector.sectorName}</CardSubtitle> : null}
            <CardSubtitle>Şehir : {product.internCity.cityName}</CardSubtitle>
            <CardSubtitle>Pozisyon : {product.internPosition.positionName}</CardSubtitle>
            İlan Sahibi :
  <a href={'profil/@'+product.user.username} target="_blank"> <p>{product.user.name} {product.user.surname} <br></br>@{product.user.username}</p> </a> <CardText className="text-muted text-small mb-0 font-weight-light">
            {Moment(product.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(product.createdAt.date).startOf('hour').fromNow()
            }</p>
                </CardText>
          </CardBody>
          
        </Card>
       
     
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(ImageListView);

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
          <CardSubtitle>İlan Tipi : {product.jobType === true ? "İş Arıyorum" : "Çalışan Arıyorum"}</CardSubtitle>
          İlan Başlığı :
          <NavLink to={'/is-ilani/'+product.slug}>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
             
              <CardSubtitle> {product.jobTitle}</CardSubtitle>
            
               
              </Colxx>
              
            </Row>
            <hr className="my-1" />
            </NavLink>
            {product.jobType === false ? <CardSubtitle>Şirket : {product.jobCompany}</CardSubtitle> : null}
            <CardSubtitle>Şehir : {product.jobCity.cityName}</CardSubtitle>
            <CardSubtitle>Pozisyon : {product.jobPosition.positionName}</CardSubtitle>
            İlan Sahibi :
  <a href={'profil/@'+product.user.username} target="_blank"> <p>{product.user.name} {product.user.surname} <br></br>@{product.user.username}</p> </a> <CardText className="text-muted text-small mb-0 font-weight-light">
            {Moment(product.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(product.createdAt.date).startOf('hour').fromNow()
            }</p>
                </CardText>
                {
                  localStorage.getItem('user') && JSON.parse(localStorage.getItem('user')).username == product.user.username ?
            <NavLink to={"/is-ilani-guncelle/"+product.slug}>
                <Button>
             İlanı Güncelle
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

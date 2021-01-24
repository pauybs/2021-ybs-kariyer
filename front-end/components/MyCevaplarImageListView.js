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
          Soru Başlığı :
          <NavLink to={'/soru/'+product.question.slug}>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
             
              <CardSubtitle> {product.question.questionTitle}</CardSubtitle>
              <p >{product.answer}</p>
               
              </Colxx>
              
            </Row>
            <hr className="my-1" />
            </NavLink>
            Soru Sahibi :
  <a href={'/profil/@'+product.question.user.username} target="_blank"> <p>{product.question.user.name} {product.question.user.surname} <br></br>@{product.question.user.username}</p> </a> <CardText className="text-muted text-small mb-0 font-weight-light">
            {Moment(product.question.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(product.question.createdAt.date).startOf('time').fromNow()
            }</p>
                </CardText>
                {
                  localStorage.getItem('user') && JSON.parse(localStorage.getItem('user')).username == product.user.username ?
            <NavLink to={"/cevap-guncelle/"+product.id}>
                <Button>
              Cevabı Güncelle
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

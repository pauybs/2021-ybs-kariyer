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



const AdminImageListView = ({ product, isSelect, collect, onCheckItem }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="6" className="mb-3" key={product.id}>
      
      <ContextMenuTrigger id="menu_id" data={product.id} collect={collect}>
      <NavLink to={"/profil/@"+product.user.username}>
        <Card
          onClick={event => onCheckItem(event, product.id)}
          className={classnames({
            active: isSelect
          })}
        >
         
          
          
          <CardBody>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
            <CardSubtitle>
            <p className="text-muted mb-0 text-small"> İletişime Geç </p>
            </CardSubtitle>
                               
                <CardText className="text-muted text-small mb-0 font-weight-light">
                {product.user.name}  {product.user.surname}
                <br></br>
                <br></br>
                @{product.user.username}
                </CardText>
               
                <Button  outline color="secondary" className="mb-2 m-1">
                  Profil
                </Button>
               
              </Colxx>
            </Row>
          </CardBody>
        </Card>
        </NavLink>
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(AdminImageListView);

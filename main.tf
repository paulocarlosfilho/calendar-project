# Configuração do Provedor AWS
provider "aws" {
  region = var.aws_region
}

# -------------------------------------------------------------
# 1. Componentes de Rede (VPC, Subnet e Internet Gateway)
# -------------------------------------------------------------
resource "aws_vpc" "main" {
  cidr_block           = "10.0.0.0/16"
  enable_dns_support   = true
  enable_dns_hostnames = true

  tags = { Name = "${var.project_name}-VPC" }
}

resource "aws_subnet" "public" {
  vpc_id                  = aws_vpc.main.id
  cidr_block              = "10.0.1.0/24"
  map_public_ip_on_launch = true # Subnet pública
  availability_zone       = "${var.aws_region}a"

  tags = { Name = "${var.project_name}-PublicSubnet" }
}

resource "aws_internet_gateway" "gw" {
  vpc_id = aws_vpc.main.id
  tags = { Name = "${var.project_name}-IGW" }
}

resource "aws_route_table" "route_public" {
  vpc_id = aws_vpc.main.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.gw.id
  }
}

resource "aws_route_table_association" "public_assoc" {
  subnet_id      = aws_subnet.public.id
  route_table_id = aws_route_table.route_public.id
}

# -------------------------------------------------------------
# 2. Security Groups (Firewall)
# -------------------------------------------------------------

# Grupo de Segurança para o EC2 (Permite SSH e HTTP na porta 8080)
resource "aws_security_group" "ec2_sg" {
  vpc_id = aws_vpc.main.id
  name   = "${var.project_name}-EC2-SG"

  # Acesso SSH (porta 22) de qualquer lugar
  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"] 
    cidr_blocks = [var.my_ip] # MELHORIA: Restringe o acesso SSH ao seu IP
  }

  # Acesso HTTP da aplicação (porta 8080 - mapeada pelo Docker) de qualquer lugar
  ingress {
    from_port   = 8080
    to_port     = 8080
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # Saída (outbound) de qualquer tráfego
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# Grupo de Segurança para o RDS (Permite acesso apenas do EC2)
resource "aws_security_group" "rds_sg" {
  vpc_id = aws_vpc.main.id
  name   = "${var.project_name}-RDS-SG"

  # Acesso MySQL (porta 3306) APENAS do Security Group do EC2
  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.ec2_sg.id]
  }

  # Saída padrão
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# -------------------------------------------------------------
# 3. Amazon RDS (Banco de Dados MySQL Free Tier)
# -------------------------------------------------------------
resource "aws_db_instance" "calendar_db" {
  allocated_storage    = 20
  storage_type         = "gp2"
  engine               = "mysql"
  engine_version       = "8.0"
  instance_class       = "db.t2.micro" # TIPO FREE TIER
  identifier           = "calendar-db-instance"
  db_name              = "meu_calendario" # Nome do DB
  username             = var.db_username
  password             = var.db_password
  parameter_group_name = "default.mysql8.0"
  skip_final_snapshot  = true
  publicly_accessible  = false # Não acessível publicamente (mais seguro)
  vpc_security_group_ids = [aws_security_group.rds_sg.id]
  db_subnet_group_name   = aws_db_subnet_group.rds_subnet_group.name

  # Para fins do Free Tier, use single-AZ e sem réplicas
}

resource "aws_db_subnet_group" "rds_subnet_group" {
  name       = "${var.project_name}-rds-sng"
  subnet_ids = [aws_subnet.public.id] # Usamos a subnet pública para simplificar a demo

  tags = {
    Name = "${var.project_name} RDS Subnet Group"
  }
}

# -------------------------------------------------------------
# 4. Amazon EC2 (Servidor de Aplicação PHP/Docker Free Tier)
# -------------------------------------------------------------

# AMI ID (Amazon Linux 2023 - otimizada para Docker)
# Nota: Você pode precisar atualizar este ID para a sua região se us-east-1 não for a sua escolha.
data "aws_ami" "amazon_linux" {
  most_recent = true
  owners      = ["amazon"]

  filter {
    name   = "name"
    values = ["al2023-ami-2023*x86_64"]
  }
}

resource "aws_instance" "calendar_app" {
  ami           = data.aws_ami.amazon_linux.id
  instance_type = "t2.micro" # TIPO FREE TIER
  key_name      = var.key_name
  subnet_id     = aws_subnet.public.id
  vpc_security_group_ids = [aws_security_group.ec2_sg.id]
  associate_public_ip_address = true

  # Script de inicialização (instala Docker e Docker Compose, e clona o seu projeto)
  user_data = <<-EOF
              #!/bin/bash
              sudo yum update -y
              yum update -y
              # Instalar Git
              yum install git -y

              # Instalar Docker
              sudo yum install docker -y
              sudo systemctl start docker
              sudo usermod -a -G docker ec2-user
              yum install docker -y
              systemctl start docker
              systemctl enable docker
              usermod -a -G docker ec2-user
              
              # Instalar Docker Compose
              sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
              sudo chmod +x /usr/local/bin/docker-compose
              sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
              curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
              chmod +x /usr/local/bin/docker-compose

              # Clonar seu projeto (ATENÇÃO: Substitua pelo seu repositório Git real)
              # Esta é a parte que você precisa de um repo público ou configurar acesso SSH/Token
              # Por simplicidade, assumiremos que o projeto está pronto para ser baixado.
              # Exemplo (Você precisa clonar o seu projeto real aqui)
              # sudo git clone <SEU_REPO_GIT_AQUI> /home/ec2-user/calendar-project
              # cd /home/ec2-user/calendar-project
              
              # Execute o Docker Compose (Assumindo que o código foi colocado na pasta)
              # Nota: O código de inicialização do DB (init.sql) rodará se você usar o volume
              # sudo docker-compose up -d --build
              
              echo "Script de inicialização concluído. Você precisará de SSH para rodar o Docker Compose."
              # Clonar o projeto
              # ATENÇÃO: Substitua pela URL do seu repositório. Deve ser público para este script funcionar.
              git clone https://github.com/paulocarlosfilho/calendar-project.git /home/ec2-user/calendar-project
              cd /home/ec2-user/calendar-project

              # Criar o arquivo .env para o Docker Compose usar
              # Ele vai injetar o endereço do RDS e as credenciais no ambiente do contêiner
              # NOTA: Seu docker-compose.yml precisa ser ajustado para ler este arquivo .env
              cat <<EOT > .env
DB_HOST=${aws_db_instance.calendar_db.address}
DB_DATABASE=${aws_db_instance.calendar_db.db_name}
DB_USERNAME=${var.db_username}
DB_PASSWORD=${var.db_password}
EOT

              # Iniciar a aplicação com Docker Compose
              # O comando é executado como ec2-user para permissões corretas
              su - ec2-user -c "cd /home/ec2-user/calendar-project && /usr/local/bin/docker-compose up -d --build"
              EOF

  tags = {
    Name = "${var.project_name}-AppServer"
  }
}
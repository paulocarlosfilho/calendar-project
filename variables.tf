# Região da AWS onde os recursos serão provisionados
variable "aws_region" {
  description = "A região da AWS para implantar os recursos."
  type        = string
  default     = "us-east-1" # Região com boa disponibilidade de Free Tier
}

# Credenciais do Banco de Dados RDS
variable "db_username" {
  description = "Nome de usuário mestre para o RDS MySQL."
  type        = string
  default     = "root"
}

variable "db_password" {
  description = "Senha mestre para o RDS MySQL."
  type        = string
  sensitive   = true # Marca como sensível para não exibir no output
}

# Chave SSH (necessária para acessar o EC2)
variable "key_name" {
  description = "Nome de uma chave EC2 existente (ex: my-ssh-key)."
  type        = string
}

# Nome do projeto (usado para tags)
variable "project_name" {
  description = "Nome do projeto para identificação de recursos."
  type        = string
  default     = "CalendarProject"
}

variable "my_ip" {
  description = "Seu endereço IP para permitir acesso SSH ao EC2. Use '/32' no final (ex: 123.45.67.89/32)."
  type        = string
}
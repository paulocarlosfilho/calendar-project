# IP Público do Servidor de Aplicação
output "app_server_public_ip" {
  description = "IP Público para acessar o servidor de aplicação."
  value       = aws_instance.calendar_app.public_ip
}

# Endpoint do Banco de Dados (necessário para o connection.php)
output "rds_endpoint" {
  description = "Endpoint do banco de dados RDS."
  value       = aws_db_instance.calendar_db.endpoint
}

# URL de acesso ao calendário (Porta 8080)
output "calendar_url" {
  description = "URL para acessar o calendário na porta 8080."
  value       = "http://${aws_instance.calendar_app.public_ip}:8080"
}
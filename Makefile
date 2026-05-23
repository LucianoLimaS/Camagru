# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Makefile                                           :+:      :+:    :+:    #
#                                                     +: .+.+    :+:    :+:    #
#    By: camagru <camagru@student.42.fr>            +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2026/05/23 01:40:00 by camagru           #+#    #+#              #
#    Updated: 2026/05/23 01:40:00 by camagru          ###   ########.fr        #
#                                                                              #
# **************************************************************************** #

NAME = camagru

all: up

up:
	docker compose up -d --build

down:
	docker compose down

clean:
	docker compose down -v

fclean: clean
	docker compose down -v --rmi all

re: fclean up

status:
	docker compose ps

logs:
	docker compose logs -f

.PHONY: all up down clean fclean re status logs

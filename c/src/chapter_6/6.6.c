#include <stdio.h>
#include <string.h>

int main(void)
{
	char word1[20];
	char word2[20];
	
	printf("\nType in the first word (less than 20 characters) : \n1: ");
	scanf("%19s", word1);	
	printf("\nType in the first word (less than 20 characters) : \n2: ");
	scanf("%19s", word2);
	
	if (strcmp(word1, word2) == 0) {
		printf("You have entered identical words");	
	} else {
		printf("%s precedes %s\n", (strcmp(word1, word2) < 0) ? word1 : word2, (strcmp(word1, word2) < 0) ? word2 : word1);	
	}

	return 0;
}
